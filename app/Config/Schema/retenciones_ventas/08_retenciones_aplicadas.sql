-- =====================================================================
-- RETENCIONES EN VENTAS - Migración 08: retenciones aplicadas (paso 6)
--
-- 1. abonofacturas pasa de MyISAM a InnoDB para poder deshacer (rollback) un
--    pago completo si algo falla: abono + saldo de la CxC + caja + retenciones.
--    No se agregan llaves foráneas a abonofacturas: tiene abonos históricos que
--    apuntan a facturas que ya no existen (143 al revisar) y no se tocan.
--
-- 2. Tabla nueva `retenciones_aplicadas`: lo que el cliente REALMENTE retuvo.
--    Diferencia con factura_retenciones (snapshot del paso 5):
--      * factura_retenciones  = lo CALCULADO al facturar (informativo, nunca cambia).
--      * retenciones_aplicadas = lo APLICADO al recibir el pago (definitivo).
--    Se registra:
--      * al facturar (origen FACTURACION): línea "Retenciones" en la ventana de
--        medios de pago; el dinero que entra a caja es total - retenciones.
--      * al pagar la cuenta por cobrar (origen PAGO_CXC), ligada al abono.
--    Una fila por tipo de retención (ReteFuente, ReteIVA, ReteICA) por evento.
--    El detalle por producto/servicio y la tarifa siguen en factura_retenciones.
--
--    valor_calculado = lo que el sistema sugirió (lo pendiente por aplicar).
--    valor           = lo que el usuario confirmó. Si difiere, editado_manual = 1.
--    certificado_*   = se usan en el reporte de retenciones (paso 7).
--
-- Antes de ejecutar: respaldo de abonofacturas, cuentasclientes, cuentas,
--   factura_cuenta_valores, factura_retenciones y facturas.
-- Después: borrar app/tmp/cache/models/myapp_cake_model_default_miggoweb_nc_abonofacturas
--   y app/tmp/cache/persistent/myapp_cake_core_method_cache.
--
-- No es idempotente en la parte 1 (el ALTER se puede repetir sin daño; la
-- tabla usa IF NOT EXISTS).
-- =====================================================================
SET NAMES utf8mb4;

-- ----------------------------- UP ------------------------------------

-- 1. abonofacturas a InnoDB (11.7 mil filas, segundos)
ALTER TABLE `abonofacturas` ENGINE = InnoDB;

-- Índices para las consultas de pagos y retenciones por factura y por CxC
ALTER TABLE `abonofacturas`
  ADD INDEX `idx_abonofacturas_factura` (`factura_id`),
  ADD INDEX `idx_abonofacturas_cuentacliente` (`cuentacliente_id`);

-- 2. Retenciones aplicadas
CREATE TABLE IF NOT EXISTS `retenciones_aplicadas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `empresa_id` INT NOT NULL,
  `factura_id` INT NOT NULL,
  `cliente_id` INT NULL,
  `origen` ENUM('FACTURACION','PAGO_CXC') NOT NULL,
  `abonofactura_id` INT NULL COMMENT 'Abono del pago de CxC (origen PAGO_CXC)',
  `cuentascliente_id` INT NULL COMMENT 'Cuenta por cobrar pagada (origen PAGO_CXC)',
  `tributo_codigo` VARCHAR(5) NOT NULL COMMENT '05 ReteIVA, 06 ReteFuente, 07 ReteICA',
  `nombre` VARCHAR(60) NOT NULL,
  `base` DECIMAL(18,2) NOT NULL DEFAULT 0.00 COMMENT 'Suma de las bases del snapshot para el tributo',
  `valor_calculado` DECIMAL(18,2) NOT NULL DEFAULT 0.00 COMMENT 'Lo que sugirió el sistema',
  `valor` DECIMAL(18,2) NOT NULL COMMENT 'Lo que confirmó el usuario',
  `editado_manual` TINYINT(1) NOT NULL DEFAULT 0,
  `certificado_recibido` TINYINT(1) NOT NULL DEFAULT 0,
  `certificado_archivo` VARCHAR(255) NULL,
  `certificado_fecha` DATE NULL,
  `anulado` TINYINT(1) NOT NULL DEFAULT 0,
  `fecha` DATE NOT NULL COMMENT 'Fecha del pago',
  `usuario_id` INT NOT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ret_apl_factura` (`factura_id`, `tributo_codigo`, `anulado`),
  KEY `idx_ret_apl_reporte` (`empresa_id`, `cliente_id`, `tributo_codigo`, `fecha`),
  KEY `idx_ret_apl_abono` (`abonofactura_id`),
  CONSTRAINT `fk_ret_apl_factura` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ret_apl_abono` FOREIGN KEY (`abonofactura_id`) REFERENCES `abonofacturas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Verificación
SELECT table_name, engine FROM information_schema.tables
WHERE table_schema = DATABASE() AND table_name IN ('abonofacturas', 'retenciones_aplicadas');

-- ---------------------------- DOWN -----------------------------------
-- DROP TABLE IF EXISTS `retenciones_aplicadas`;
-- ALTER TABLE `abonofacturas` DROP INDEX `idx_abonofacturas_cuentacliente`, DROP INDEX `idx_abonofacturas_factura`;
-- ALTER TABLE `abonofacturas` ENGINE = MyISAM;
