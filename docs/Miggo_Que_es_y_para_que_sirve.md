# Qué es Miggo y para qué sirve

> Fuentes: el menú de navegación de Miggo y sus textos de ayuda (tabla `cloudmenus`), el código de la aplicación y los conteos de la base `miggoweb_nc`. Los conteos salen de la copia de base de datos de este entorno y pueden diferir de producción.

---

## 1. Miggo en pocas palabras

**Miggo es un sistema en la nube para administrar un negocio de punta a punta: lo que se vende, lo que hay en bodega, lo que se debe cobrar y pagar, el cliente que compra y, en el caso de los talleres, el vehículo que se atiende.**

Se usa desde el navegador, sin instalar nada. Cada empresa cliente trabaja con su propia información, sus propios usuarios y su propia licencia, aunque todas corren sobre la misma plataforma.

Miggo nació para dos tipos de negocio que comparten la misma necesidad (vender, controlar inventario y cobrar):

1. **Comercios y distribuidores con inventario**: venden productos desde una o varias bodegas o sedes.
2. **Talleres automotrices (carros y motos)**: reciben un vehículo, abren una orden de trabajo, cobran el servicio y mantienen la relación con el cliente después de entregarlo.

Lo que distingue a Miggo de un programa de facturación común es que **junta en una sola herramienta la parte administrativa del negocio con la operación de taller y el seguimiento al cliente**.

---

## 2. Para qué sirve (problemas que resuelve)

| El dueño o administrador necesita… | Miggo le permite… |
|---|---|
| Vender rápido y facturar legalmente | Facturar desde un mostrador ágil ("caja rápida") o con documento completo, y emitir **factura electrónica válida ante la DIAN**. |
| Saber qué tiene y dónde | Controlar existencias por **bodega o sede**, trasladar mercancía entre ellas, ajustar diferencias y cargar conteos físicos desde Excel. |
| No perder plata en cartera | Ver quién le debe, cuánto y desde cuándo (cuentas por cobrar con alertas de vencimiento), registrar abonos y emitir recibos de caja. |
| Controlar lo que debe y lo que gasta | Registrar compras, gastos y cuentas por pagar con su vencimiento. |
| Cuadrar la caja cada día | Hacer el **cierre diario**: lo facturado, lo cobrado por cada medio de pago y a qué cuenta de caja entró. |
| Saber si está ganando | Ver la **utilidad por producto vendido**, la **rotación del inventario** y las ventas por cliente. |
| Organizar el trabajo del taller | Recibir el vehículo, abrir la **orden de trabajo**, asignarla a un mecánico, seguirla con semáforos por días y pasarla a factura al terminar. |
| No perder al cliente | Gestionar **alertas de fidelización** (llamadas de seguimiento), calendario de citas y eventos. |
| Cumplir con la normativa colombiana | Manejar IVA, impuesto al consumo, impuesto a las bolsas y **retenciones** (ReteIVA, ReteFuente, ReteICA) con sus reportes y certificados. |
| Controlar quién hace qué | Crear usuarios con **perfiles** que ven solo las opciones que les corresponden, con auditoría de acciones. |

---

## 3. Mapa funcional

El menú de Miggo se organiza en once áreas. Esta es la visión de conjunto, sin entrar en el detalle de cada pantalla.

### 3.1 Facturación (ventas y caja)
Es el corazón diario del negocio.
- **Documento de venta, factura electrónica y caja rápida**: tres formas de vender según la necesidad. La caja rápida muestra las imágenes de los productos.
- **Prefacturas**: ventas o servicios listos para facturar, con control de lo que está pendiente.
- **Cotizaciones**: precios para clientes externos antes de la venta.
- **Gestión de facturas** y **notas crédito**.
- **Cuentas por cobrar** y **recibos de caja**.
- **Cuentas por pagar** y **gastos**.
- **Cierre diario** y su histórico.
- **Tipos de pago** y **cuentas / cajas**: a qué cuenta entra cada pago (efectivo, transferencia, cheque).

### 3.2 Inventario
- Catálogo de **productos** y **categorías**, con precios, códigos y códigos de barras.
- **Inventario por bodega** con disponibilidad y precio.
- **Cargue** de inventario (incluye carga masiva por Excel de un conteo físico), **descargue** para ajustes y **traslados** entre bodegas.
- **Proveedores**.
- Alerta de **bajo stock** en la pantalla principal.

### 3.3 Bodegas
- Creación de **bodegas o sedes** y sus tipos.
- Asignación de **usuarios y clientes a cada bodega**: cada usuario ve y opera solo las bodegas que tiene habilitadas.
- Vista del comportamiento y rotación de cada bodega.

### 3.4 Órdenes de trabajo (taller)
- **Orden de trabajo** al recibir el vehículo: cliente, vehículo, kilometraje, llaves, documentos, herramientas, SOAT, tecnomecánica y observaciones.
- **Plantas de servicio** (en qué sede o bodega se atiende).
- **Semáforos**: las órdenes se pintan por color según los días que llevan abiertas.
- **Reporte de órdenes por mecánico**, útil para revisar comisiones.

### 3.5 Vehículos
- Catálogo de **marcas, tipos, líneas y partes de vehículo** (para autos o motos), de modo que cada orden se diligencia sobre el vehículo y sus partes reales.

### 3.6 Clientes
- Base de **clientes** con datos de contacto y datos tributarios, y **notas o anotaciones** sobre cada uno.

### 3.7 Compras
- **Compras a proveedores**, categorías de compra y sus **retenciones** (ReteICA y ReteFuente aplicadas al comprar).

### 3.8 CRM (relación con el cliente)
- **Alertas de orden de trabajo, de factura y generales**: llamadas de fidelización y seguimiento, con estado pendiente o finalizada.
- **Calendario** de alertas y citas, y **eventos** con responsable, fecha y datos del cliente.

### 3.9 Reportes
- **Utilidad por ventas** y **rotación de productos**.
- **Pagos por factura** y **por abono**: qué medio de pago se usa más.
- **Facturas por cliente**: servicios más vendidos y clientes frecuentes.
- **Notas crédito**.
- **Retenciones**: las que los clientes le practicaron a la empresa, certificados pendientes, resumen del periodo y diferencias.
- **Prefacturas y órdenes en gestión**: lo que falta por facturar.
- **Análisis gerencial con IA** sobre algunos reportes, por ejemplo el cierre diario (usa un servicio de inteligencia artificial de Google).

### 3.10 Administración
- **Mi empresa**: los datos que aparecen en la factura (razón social, NIT, logo, textos).
- **Usuarios, perfiles y menús por perfil**.
- **Relacionar empresa**: facturar con otra "personalidad" del cliente, natural o jurídica, según el caso.
- **Impuestos**, **notas para facturas**, **ítems de gasto**.
- **Publicidad**: imágenes en la cabecera y el pie de la aplicación móvil del cliente.
- **Cambio de contraseña**.

### 3.11 Información (vista del cliente final)
- Opciones para que el cliente consulte **sus facturas y sus órdenes de trabajo**. Por el menú y los textos de ayuda, Miggo cuenta con una aplicación móvil donde el cliente ve el historial de su vehículo y las órdenes en proceso o terminadas.

### Gestión de la plataforma (solo para quien administra Miggo)
Existe un grupo de pantallas para el equipo de Miggo: empresas, **licencias**, asignación de licencias a empresas, perfiles, menús, régimen, ciudades, países y auditorías. Las empresas clientes no las ven.

---

## 4. Cómo se conecta todo (un ciclo de negocio)

**Negocio comercial**
1. Se crea el **producto** y se **carga el inventario** en una bodega.
2. Un cliente compra: se hace una **prefactura o venta**, que descuenta inventario.
3. Se **factura** (con factura electrónica si aplica) y se registra el **pago**: contado entra a una caja o cuenta, y crédito queda como **cuenta por cobrar**.
4. Al final del día, el **cierre diario** cuadra lo vendido contra lo cobrado.
5. Los **reportes** muestran utilidad, rotación y los mejores clientes.

**Taller**
1. Llega el vehículo: se abre la **orden de trabajo** con sus datos y partes.
2. Se asigna a un mecánico y la orden avanza con **semáforos**.
3. Al terminar, la orden pasa a **prefactura** y luego a **factura**.
4. Se registra una **alerta de fidelización** para llamar al cliente después del servicio.
5. El cliente puede consultar su historial desde la **aplicación móvil**.

---

## 5. Cómo está organizado como servicio

- **Multiempresa**: una sola plataforma atiende a muchas empresas, cada una con sus datos separados.
- **Sedes y centros de costo**: una empresa puede operar varias bodegas y facturar en nombre de una sede.
- **Licencias por empresa**: cada empresa tiene una licencia con fecha de vencimiento y un **número máximo de usuarios conectados a la vez**. Si la licencia vence o se llena el cupo, el ingreso se bloquea.
- **Perfiles de usuario**: cada usuario ve solo las opciones de su perfil, y un usuario se bloquea tras tres intentos fallidos.
- **Auditoría**: se registran acciones sensibles.

---

## 6. Cumplimiento en Colombia

- **Factura electrónica ante la DIAN**: Miggo arma la factura y la envía a un servicio externo (basado en APIDIAN) que la firma, la transmite y devuelve el CUFE y el código QR. Cada empresa tiene su propia resolución, consecutivo y credencial.
- **Facturación no electrónica**: también se puede emitir documento de venta interno.
- **Impuestos**: IVA, impuesto nacional al consumo (INC) e impuesto al consumo de bolsas.
- **Retenciones en ventas**: ReteIVA, ReteFuente y ReteICA, con base mínima en UVT, reglas por empresa y reportes para conciliar contra los certificados.
- **Datos tributarios** del cliente y de la empresa: tipo de documento, dígito de verificación y responsabilidades fiscales.

---

## 7. Qué tan grande es hoy (según la base `miggoweb_nc`)

| Concepto | Cantidad |
|---|---|
| Empresas registradas | **107** (79 con facturas) |
| Usuarios | 430 |
| Clientes finales | ~36.000 |
| Productos | ~55.000 |
| Bodegas / depósitos | 676 |
| Facturas emitidas | ~79.600 (de ellas ~20.300 electrónicas) |
| Prefacturas | ~87.000 |
| Órdenes de trabajo | ~5.500 |
| Vehículos registrados | ~5.300 |
| Compras | ~3.900 |
| Gastos | ~20.200 |
| Cuentas por cobrar | ~12.400 |
| Cierres diarios | ~13.700 |
| Empresas con facturación electrónica configurada | 32 |
| Licencias | 107 registros: 85 activas y 22 vencidas, con unos 10 usuarios simultáneos por empresa en promedio |

Las licencias se venden por periodos distintos: 3, 31, 45, 62, 90 y 730 días.

---

## 8. Qué no hace Miggo (según el menú actual)

- No es un sistema contable: no genera asientos ni se integra con un software de contabilidad (hoy no hay integración con TRIUCO).
- No incluye nómina, producción ni manufactura.
- No transmite por sí mismo a la DIAN: depende del servicio externo de facturación electrónica.

---

## 9. Cómo explicarlo en 30 segundos

> "Miggo es un sistema en la nube para administrar el día a día de un negocio colombiano: vende y factura electrónicamente, controla el inventario por bodega, lleva la cartera y la caja, y si es un taller, gestiona los vehículos y las órdenes de trabajo y hace seguimiento al cliente después del servicio. Cada empresa tiene su propia licencia y sus usuarios, y todo cumple con la DIAN: facturación electrónica, impuestos y retenciones."

---

## 10. Datos por confirmar

- Qué porcentaje de las 107 empresas son talleres y cuántas son comercios. Lo deduje del producto (existen módulos de taller), pero la base no tiene un campo de "tipo de negocio".
- Si la aplicación móvil del cliente es un producto propio de Miggo, y quién la mantiene. El código de este repositorio solo gestiona su publicidad.
- Cuántas de las 85 licencias activas son de pago y cuántas son pruebas (hay licencias de 3 días).
