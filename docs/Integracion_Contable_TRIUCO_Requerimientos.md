# Integración Miggo ↔ API Contable (TRIUCO): análisis y requerimientos

**Fuente analizada:** `API Contable.postman_collection.json` (colección Postman, 80 KB, ~100 endpoints en 30 carpetas) y el estado actual de Miggo (código y base `miggoweb_nc`).
**Objetivo:** que Miggo envíe su operación a la aplicación contable y las empresas que lo contraten tengan el "complemento contable".

> **Límite de este análisis.** La colección trae las peticiones (URL, headers y cuerpo de ejemplo) pero **ninguna respuesta de ejemplo, ningún código de error y ninguna descripción de reglas de validación**. Todo lo que dice este documento sobre el comportamiento de la API es una deducción de los cuerpos de ejemplo. Lo que no se puede saber desde la colección está en la sección 9 como preguntas para el equipo de TRIUCO.

---

## 1. Qué es la API contable, según la colección

- **Servicio:** `https://contabilidad.ajdigitalsales.com/api/` (variable `url`). No se sabe si es producción o pruebas.
- **Autenticación:** `POST login` con email y contraseña, que devuelve un token. Luego se usa `Authorization: Bearer {token}`. Hay `refresh` y `logout`. La colección no guarda el token de la respuesta, así que no sabemos su nombre en el JSON ni su vigencia.
- **Multiempresa:** casi todas las rutas llevan el header `X-Company-Id` (variable `company`, valor de ejemplo `7`). Una sola cuenta de usuario puede operar varias empresas.
- **Los identificadores son numéricos y propios de TRIUCO**: `client_id`, `supplier_id`, `product.id`, `resolution_id`, `fin_bank_account_id`, `chart_of_account_id`, `document_id`, `cost_center_id`. Miggo no puede mandar sus propios códigos; necesita traducirlos.
- **Cubre una contabilidad completa**, no solo recibir facturas: catálogo de cuentas (PUC), documentos y transacciones contables, períodos fiscales, bancos y conciliación, impuestos y retenciones, reportes (estado de resultados, balance, cartera, auxiliar), exógena y certificados tributarios. También tiene su propia facturación electrónica ante la DIAN.

### Endpoints por grupo y su utilidad para Miggo

| Grupo | Endpoints | Para qué los usaría Miggo |
|---|---|---|
| Auth | login, refresh, logout, me | Conexión del servicio. |
| Empresas | listar, obtener, crear, editar | Dar de alta cada empresa de Miggo en TRIUCO. |
| Entidades (terceros) | listar, obtener, crear (natural y jurídica), editar, eliminar | Sincronizar clientes y proveedores. |
| Productos | listar, crear (no hay editar) | Sincronizar el catálogo. |
| Facturas de venta | listar, crear (sin número / con número), obtener | Ventas. |
| Compras | listar, crear (sin / con `document_id`), obtener | Compras a proveedores. |
| Documento soporte | listar, crear | Compras a no obligados a facturar. |
| **Integración** | **crear factura externa, consultar por `external_id`, crear compra externa** | **Camino pensado para sistemas como Miggo.** |
| Transacciones / Posteo | listar, crear asiento, balance de comprobación, formulario, siguiente número | Asientos manuales: gastos, recibos, ajustes. |
| PUC, centros de costo, resoluciones, configuración contable | listar y crear | Configuración inicial por empresa. |
| Cuentas bancarias, movimientos, conciliación | listar, crear, registrar gasto/ingreso, marcar/desmarcar | Cajas, bancos y gastos. |
| Impuestos y retenciones | listar y crear (`taxconfig`, `withholdingsconfig`) | IVA, INC y retenciones con sus cuentas. |
| Notas crédito | **solo** listar, obtener, PDF | Consulta únicamente. |
| DIAN | JSON, XML, PDF, enviar, cancelación | TRIUCO también factura electrónica (ver 4.1). |
| Reportes, exógena, certificados | consultas | Mostrar o enlazar desde Miggo. |

---

## 2. Qué se integraría (alcance funcional propuesto)

Cada fila es un evento de Miggo que debe llegar a la contabilidad.

| # | Evento en Miggo | Destino en TRIUCO | Estado |
|---|---|---|---|
| 1 | Factura de venta (los tres flujos: add, prefacturas, caja rápida) | `integration.invoices` | Cubierto |
| 2 | Retenciones en ventas (snapshot ya implementado) | `withholdings` dentro de cada ítem | Cubierto, formato por confirmar |
| 3 | Pago al facturar (contado, varios medios de pago) | `payment_methods` de la factura | Cubierto |
| 4 | Abono a cuenta por cobrar (con o sin retenciones aplicadas) | No hay endpoint de recaudo | **Brecha** (usar asiento) |
| 5 | Recibo de caja | No hay endpoint | **Brecha** (usar asiento) |
| 6 | Nota crédito | Solo consulta | **Brecha** |
| 7 | Anulación o eliminación de factura | No hay endpoint | **Brecha** |
| 8 | Compra a proveedor (con IVA, ReteICA, ReteFuente) | `integration.purchases` | Cubierto |
| 9 | Pago de cuenta por pagar | No hay endpoint | **Brecha** (asiento) |
| 10 | Gasto | `banks.movements.{id}.expense-income` o asiento | Cubierto, por confirmar |
| 11 | Traslado entre cuentas de caja | Asiento | Cubierto con asiento |
| 12 | Movimientos de inventario (cargue, descargue, traslado) y costo de ventas | No se ve un endpoint | **Por definir** (¿lo maneja TRIUCO?) |
| 13 | Clientes y proveedores | `entity` | Cubierto |
| 14 | Productos | `products` | Cubierto (con cuentas contables) |
| 15 | Impuestos, retenciones y resoluciones | `taxconfig`, `withholdingsconfig`, `resolutions` | Cubierto |

---

## 3. Lo que Miggo necesita construir

### 3.1 Cliente de la API (capa de conexión)
- Clase que haga login, guarde el token, lo renueve al vencer y reintente una vez ante un 401.
- Manejo de errores uniforme (timeout, 4xx de validación, 5xx), con registro del mensaje devuelto por TRIUCO.
- **Credenciales fuera del código**: en configuración del servidor o en `configuraciondatos` cifrado, nunca en el repositorio ni en los logs.
- La URL base debe ser configurable para apuntar a pruebas o producción.

### 3.2 Cola de envío (outbox) en lugar de enviar en línea
Facturar no debe depender de que la contabilidad esté disponible. Propuesta:

- Tabla `contabilidad_cola`: empresa, tipo de evento, id del registro de Miggo, payload JSON, estado (pendiente / enviado / error / omitido), intentos, último error, fecha de envío, id devuelto por TRIUCO.
- Miggo inserta el evento **dentro de la misma transacción** de la operación (igual que se hizo con las retenciones). Si falla la inserción, la operación sigue y se registra el error, como ya se decidió para el snapshot de retenciones.
- Un proceso por consola (`cake contabilidad enviar`), programado cada pocos minutos, toma los pendientes en orden y los envía. Reintenta con espera creciente.
- **El orden importa**: antes de una factura deben existir su cliente y sus productos en TRIUCO. El proceso debe garantizarlo (ver 3.3).

### 3.3 Tablas de equivalencias (mapeo) Miggo ↔ TRIUCO
Cada id de Miggo necesita su id en TRIUCO, por empresa:

| Miggo | TRIUCO | Observación |
|---|---|---|
| `empresas` | `company_id` (header `X-Company-Id`) | Una empresa Miggo = una empresa TRIUCO. |
| `clientes` | `entity` (`client_id`) | 36.000 clientes en la base actual. |
| `proveedores` | `entity` (`supplier_id`) | 544 proveedores. |
| `productos` | `product.id` | 55.000 productos. |
| `resolucionfacturas` | `resolution_id` | Prefijo, rango y `document_id`. |
| `cuentas` y `tipopagos` | `fin_bank_account_id` | `tipopagos` ya tiene `cuenta_id` y `contabilizar`. |
| `impuestos` (IVA, INC, bolsa) | `taxconfig` | Cuentas de venta y de compra. |
| `impuestos` clase RETENCION | `withholdingsconfig` | Cuenta contable por retención. |
| `centroscostos` | `cost_center_id` | 5 centros hoy. |
| `ciudades` / `ciudadesmiggos` | `city_id`, `department_id`, `country` | Hay que casar contra los ids internos de TRIUCO. |
| `tipoidentificaciones` | `identification_type_id` | 12 tipos en Miggo. |

Tabla propuesta: `contabilidad_mapeo (empresa_id, tipo, miggo_id, triuco_id, creado, actualizado)` con índice único por (empresa, tipo, miggo_id).

### 3.4 Configuración por empresa y activación del complemento
- Tabla `contabilidad_empresas`: `empresa_id`, `company_id` de TRIUCO, activo sí/no, **fecha de inicio de la integración**, estado de la configuración inicial.
- El complemento se activa **por empresa**. Una empresa sin activar no genera cola.
- Pantalla de administración de Miggo para activar, ver el estado y relacionar la empresa con su `company_id`.
- Comercialmente se puede atar a la licencia (hoy las licencias son por empresa); hay que decidir si es un producto adicional.

### 3.5 Asistente de configuración inicial por empresa
Antes del primer envío debe quedar listo en TRIUCO, y Miggo debe poder verificarlo:
1. Empresa creada y relacionada.
2. **Catálogo de cuentas (PUC)** cargado.
3. **Configuración contable** (`accounting-settings`): cuentas de clientes, anticipos, proveedores, gasto e inventario.
4. **Cuentas de caja y bancos** (`fin-bank-accounts`) enlazadas a cuentas del PUC.
5. **Impuestos y retenciones** con sus cuentas de venta y de compra. La columna `impuestos.cuenta_contable` ya existe en Miggo para guardar esta cuenta.
6. **Resoluciones** equivalentes a las de Miggo.
7. **Cuenta contable de ingreso, devolución y gasto por producto** (el ejemplo de creación de producto las exige). Con 55.000 productos hace falta una regla: cuenta por categoría y una cuenta por defecto, no una por producto.

### 3.6 Sincronización de terceros y productos
- Crear en TRIUCO el cliente o proveedor **la primera vez que aparece** en una factura o compra, y guardar el mapeo.
- Datos obligatorios probables (por los ejemplos): tipo de persona, identificación, dígito de verificación, dirección, ciudad, departamento y tipo de identificación. Para jurídica: razón social, tipo de organización, responsabilidad y régimen. Miggo ya tiene el DV calculado y las responsabilidades tributarias del cliente (trabajo de retenciones).
- **Calidad de datos**: Miggo tiene clientes sin datos completos y un cliente genérico "consumidor final" (`1234567890`). Hace falta una regla para ellos (un tercero genérico por empresa) y una validación previa que avise qué clientes no se pueden sincronizar.
- La API no muestra "editar producto". Si cambia un producto en Miggo, hay que saber cómo se actualiza (pregunta 9).

### 3.7 Construcción de cada documento
- **Factura:** cliente, resolución, **número y prefijo de Miggo** (usar el ejemplo "con número", para que el consecutivo coincida con la factura electrónica ya emitida), fechas, días de crédito, ítems con base y precio, `taxes` y `withholdings` por ítem, y `payment_methods`.
- **`is_elect`:** debe ir en `0` si Miggo sigue facturando ante la DIAN (ver 4.1).
- **`external_id`:** identificador estable y único, por ejemplo `MIGGO-{empresa}-FAC-{id}`. Permite reenviar sin duplicar y consultar el estado.
- **Retenciones en ventas:** el snapshot (`factura_retenciones`) y lo realmente aplicado (`retenciones_aplicadas`, con origen FACTURACION o PAGO_CXC) son la fuente. Mapear ReteIVA 05, ReteFuente 06 y ReteICA 07 a `withholdings`. ReteICA está guardada por mil: convertir según el formato que espere TRIUCO.
- **Cálculo con 2 decimales** en todo, como en el resto de Miggo.
- Calcular los totales desde el **detalle** de la factura, no desde `facturas.pagocontado`, que hoy está en 0 por el error ya reportado.

### 3.8 Pantalla de seguimiento en Miggo
- Lista de eventos con estado, error y botón **reintentar**.
- Indicador por factura ("contabilizada" / "pendiente" / "con error") en `facturas/index`.
- Resumen de pendientes y errores por empresa para soporte.

### 3.9 Carga de histórico
Decisión de negocio: ¿desde qué fecha se contabiliza? Hay unas 79.600 facturas, 3.900 compras y 20.200 gastos. Opciones: empezar desde una **fecha de corte** (recomendado: sin histórico) o cargar el histórico con una herramienta masiva y controlada. Un histórico requiere además saldos iniciales (cartera, inventario, bancos).

### 3.10 Conciliación entre sistemas
Reporte que compare por período los totales de Miggo (ventas, IVA, retenciones, recaudos) con lo que muestra TRIUCO (balance de comprobación, cartera). Sin esto, las diferencias solo aparecen en el cierre contable.

---

## 4. Decisiones que hay que tomar antes de programar

### 4.1 Doble facturación electrónica (la más importante)
TRIUCO tiene su propio módulo DIAN (`send-dian`, XML, PDF, `electronic-billing-settings`) y Miggo ya envía por `facturaconmiggo.com` (APIDIAN). Si ambos envían la misma factura habría **dos documentos ante la DIAN**.
- **Opción recomendada:** Miggo sigue emitiendo ante la DIAN y manda a TRIUCO la factura ya emitida (`is_elect = 0`, con el número de Miggo). TRIUCO solo contabiliza.
- Alternativa: que TRIUCO emita. Obliga a rehacer el flujo de envío, el CUFE, el QR y el correo del cliente.

### 4.2 Quién numera
Enviar siempre el número de Miggo (ejemplo "con número"). Si TRIUCO asigna el suyo, los consecutivos se separan.

### 4.3 Qué hace TRIUCO con el inventario y el costo de ventas
La colección no muestra movimientos de inventario. Hay que saber si TRIUCO calcula el **costo de ventas** al recibir una factura (a partir del `cost_price` del producto) o si Miggo debe enviarlo como asiento. Miggo maneja `costopromedio` por producto y por bodega.

### 4.4 Retenciones: cuándo se contabilizan
Una retención puede aplicarse al facturar (contado) o al pagar la cuenta por cobrar. Miggo ya distingue ambas. TRIUCO debe recibirlas en el momento correcto, y el abono con retenciones necesita su propio asiento (ver brechas).

### 4.5 Qué pasa con una anulación
Miggo puede eliminar facturas (`eliminar = 1`). Si ya se contabilizó, hace falta el equivalente en TRIUCO (anulación o contra-asiento).

### 4.6 Períodos cerrados
Si un período fiscal está cerrado en TRIUCO, un documento con fecha de ese período será rechazado. Hay que definir qué hace Miggo (error visible, o enviar con la fecha del primer día abierto).

---

## 5. Brechas de la API (lo que la colección no ofrece)

| Brecha | Impacto | Alternativa si TRIUCO no la agrega |
|---|---|---|
| **No hay crear nota crédito** (solo consultar) | Las devoluciones y notas no llegan a contabilidad. | Asiento manual por `transactionspost`, sin enlace formal con la factura. |
| **No hay anular ni editar factura o compra** | Una factura eliminada en Miggo queda contabilizada. | Contra-asiento manual. |
| **No hay endpoint de recaudo** (abonos y recibos de caja) | Cartera y bancos no se mueven al cobrar. | Asiento con `is_receivable`, `ar_ap_ref` (el ejemplo de transacción trae esos campos). Hay que confirmar que así se cierra la cuenta por cobrar. |
| **No hay pago de cuentas por pagar** | Igual para proveedores. | Mismo mecanismo de asiento. |
| **No hay editar producto ni tercero en la colección** | Cambios en Miggo no se reflejan. | Confirmar si existe `PUT`, o crear y mantener el mapeo. |
| **Transacciones sin identificador externo** | Un reintento puede duplicar el asiento. | Control propio de idempotencia en la cola. Pedir a TRIUCO un `external_id`. |
| **No hay endpoints de inventario** | Ver 4.3. | Depende de la respuesta. |

---

## 6. Requisitos no funcionales

- **Seguridad.** Credenciales fuera del código, comunicación HTTPS, sin tokens en los logs. La colección entregada contiene un correo y una **contraseña de prueba débil en texto plano**; no debe subirse a git (el archivo está en la raíz del proyecto) y la contraseña debe rotarse antes de usarse con datos reales.
- **Idempotencia.** Reenviar un evento nunca debe duplicar un documento.
- **Aislamiento por empresa.** Cada llamada con el `company_id` correcto; una empresa nunca debe ver ni afectar a otra.
- **Auditoría.** Guardar qué se envió, cuándo y qué respondió TRIUCO.
- **Rendimiento.** Envío en segundo plano, con lotes pequeños y límite de ritmo. Aún no se conoce el límite de TRIUCO.
- **Observabilidad.** Alertas cuando un evento falle varias veces o la cola crezca.
- **Compatibilidad.** Los cambios en Miggo deben ser aditivos: sin la integración activada, nada cambia en facturación.

---

## 7. Plan de trabajo propuesto

| Fase | Contenido | Resultado |
|---|---|---|
| 0. Definición | Responder las preguntas de la sección 9 y las decisiones de la 4. Obtener un ambiente de pruebas de TRIUCO. | Alcance cerrado. |
| 1. Base técnica | Cliente de la API, cola, mapeos, configuración por empresa, pantalla de seguimiento. | Se puede enviar un documento de prueba y ver su estado. |
| 2. Configuración inicial | Asistente por empresa: PUC, cuentas, impuestos, retenciones, resoluciones, bancos. | Una empresa queda lista para contabilizar. |
| 3. Maestros | Terceros y productos, con validación de datos incompletos. | Clientes y productos sincronizados. |
| 4. Ventas | Factura con impuestos, retenciones y pagos. | Facturas contabilizadas. |
| 5. Recaudo | Abonos, recibos de caja, retenciones aplicadas al pagar. | Cartera y bancos al día. |
| 6. Compras y gastos | Compras, cuentas por pagar, gastos, traslados. | Egresos contabilizados. |
| 7. Correcciones | Anulaciones y notas crédito (según lo que ofrezca la API). | Reversión de documentos. |
| 8. Conciliación y salida | Reporte de diferencias, piloto con una empresa, luego activación gradual. | Complemento disponible. |

Orden sugerido de validación: primero una empresa propia de pruebas contra el ambiente de pruebas de TRIUCO, después una empresa piloto real con fecha de corte, y solo entonces activar el resto.

---

## 8. Insumos que debe entregar el negocio y TRIUCO

**De TRIUCO:**
1. Ambiente de **pruebas** separado de producción, con una empresa de prueba.
2. Documentación o ejemplos de **respuestas y errores** de cada endpoint.
3. Credenciales de servicio por ambiente, con la política de rotación.
4. Datos de contacto técnico para resolver dudas durante el desarrollo.

**De Miggo / negocio:**
1. **PUC base** a cargar en cada empresa y las **cuentas por defecto** (ingresos, costo, inventario, caja, cartera, proveedores, impuestos, retenciones).
2. Regla de cuentas por categoría de producto.
3. Cuentas contables de cada retención (ya preparado el campo `impuestos.cuenta_contable`; está pendiente consultarlo con la contadora, como ya se había anotado).
4. Fecha de corte y decisión sobre histórico.
5. Empresa piloto y empresa propia de pruebas.
6. Si el complemento se vende aparte (para atarlo a la licencia).

---

## 9. Preguntas para el equipo de TRIUCO

**Autenticación y operación**
1. ¿Cómo se llama el campo del token en la respuesta del login y cuánto dura? ¿El `refresh` lo extiende o entrega uno nuevo?
2. ¿Una misma cuenta de servicio puede operar todas las empresas con `X-Company-Id`? ¿Cómo se otorga el acceso a una empresa nueva?
3. ¿Hay límite de peticiones por minuto?
4. ¿La URL de la colección es producción o pruebas? ¿Existe otro ambiente?

**Configuración**
5. Al crear una empresa por `POST companies`, ¿se carga el PUC automáticamente o hay que cargarlo cuenta por cuenta?
6. ¿Qué es `document_id` y cómo se obtiene el de cada tipo de documento (factura, compra, documento soporte)?
7. ¿Cuál es el significado de `tax_category`, `tax_product`, `type_rate` y `withholdings_category`, y qué valores admiten?

**Documentos**
8. En `integration.invoices`, ¿cuál es el formato exacto de `taxes` y `withholdings` dentro de cada ítem? ¿La retención se envía como porcentaje, como valor o ambos?
9. ¿Qué diferencia hay entre `invoices` e `integration.invoices`? ¿Cuándo usar cada uno?
10. ¿`is_elect = 0` garantiza que TRIUCO no envía nada a la DIAN?
11. ¿Cómo se **anula** una factura o una compra? ¿Y cómo se crea una **nota crédito** (solo vemos consulta)?
12. ¿Cómo se registra un **recaudo** o abono a una factura y un **pago** a una compra? ¿El `ar_ap_ref` de una transacción cierra la cuenta por cobrar?
13. ¿Qué respuesta devuelve el crear (ids, número asignado) y qué ocurre si se repite el mismo `external_id`?
14. ¿Una factura con fecha en un período cerrado se rechaza? ¿Con qué error?

**Inventario y costos**
15. ¿TRIUCO lleva inventario? Si es así, ¿cómo recibe los movimientos y calcula el costo de ventas?
16. ¿Se puede **editar** un producto o un tercero ya creado?

**Datos**
17. ¿De dónde salen los ids de ciudad y departamento (`city_id`, `department_id`) y de tipo de identificación? ¿Hay un endpoint de consulta con código DANE para casarlos?
18. ¿Cuál es el máximo de ítems por factura y el tamaño máximo de la petición?

---

## 10. Riesgos principales

| Riesgo | Efecto | Mitigación |
|---|---|---|
| Doble emisión a la DIAN | Documentos duplicados y problemas fiscales. | Decisión 4.1 antes de codificar; `is_elect = 0`. |
| API sin anulación, nota crédito ni recaudo | Contabilidad que no refleja la realidad. | Resolver con TRIUCO antes de la fase 4. Asientos manuales como alternativa. |
| Duplicados por reintentos | Doble contabilización. | `external_id` en facturas y compras; control propio de idempotencia en el resto. |
| Datos de terceros incompletos | Rechazo de facturas. | Validación previa, tercero genérico y reporte de clientes incompletos. |
| Diferencia entre Miggo y TRIUCO por redondeo | Descuadres. | Dos decimales en todo, cálculo desde el detalle, conciliación periódica. |
| Fallas de la API que bloqueen la venta | Pérdida de ventas. | Cola asíncrona: la factura nunca espera a la contabilidad. |
| Carga masiva del histórico | Saturación y errores. | Fecha de corte; carga por lotes si el negocio la exige. |
| Credenciales en el repositorio | Fuga de acceso. | Rotar la contraseña de la colección; excluir el archivo de git. |

---

## 11. Estado actual de Miggo que ya sirve de base

- **Retenciones en ventas** implementadas: snapshot por factura (`factura_retenciones`), valores realmente aplicados (`retenciones_aplicadas`) y reportes. Es la fuente para `withholdings`.
- **Impuestos** con el campo `cuenta_contable` y el vínculo `tax_id` ya creados.
- **Pagos** por factura (`factura_cuenta_valores`) y por abono (`abonofacturas`) con su cuenta de caja.
- **Terceros** con datos tributarios, dígito de verificación y responsabilidades (el trabajo de la fase de retenciones).
- **Envío a la DIAN** por APIDIAN funcionando, con el campo `with_holding_tax_total` agregado. Esto es lo que se debe preservar (decisión 4.1).
- Un **error ajeno** que afecta a cualquier cálculo desde cabecera: `facturas.pagocontado` está en 0 desde julio. La integración debe calcular desde el detalle.
