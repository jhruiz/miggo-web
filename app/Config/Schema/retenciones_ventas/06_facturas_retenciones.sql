-- =====================================================================
-- RETENCIONES EN VENTAS - Migración 06: snapshot de retenciones en la factura
-- Documento: sección 3.9 (adaptada)
--
-- Se guarda una foto de cada retención calculada (tarifa y UVT usadas) para que
-- un cambio posterior de tarifas o de UVT no altere facturas ya emitidas.
-- Aplica solo a facturas electrónicas (facturas.factura = 1). Las ventas rápidas
-- (sin cliente) y los documentos equivalentes no generan retenciones.
--
-- Decisiones de adaptación:
--  * facturas.centrocosto_id ya existe (agregado antes); aquí no se toca.
--  * Todos los ids reales son INT con signo (no UNSIGNED), por eso las FK funcionan.
--  * created/modified en lugar de created_at/updated_at (CakePHP solo llena esos).
--  * ciudadesmiggo_id BIGINT UNSIGNED (igual que ciudadesmiggos.id), sin FK.
--  * FK a facturas (ON DELETE CASCADE) y a impuestos (sin cascade: una regla usada
--    en facturas no se puede borrar, solo desactivar o cerrar con vigente_hasta).
--
-- Nota operativa: facturas tiene ~79.600 filas; el ALTER reconstruye la tabla.
-- En producción ejecutarlo en horario de baja carga.
-- No es idempotente (ALTER). Ejecutar una sola vez.
-- =====================================================================
SET NAMES utf8mb4;

-- ----------------------------- UP ------------------------------------
ALTER TABLE `facturas`
  ADD COLUMN `total_retenciones` DECIMAL(18,2) NOT NULL DEFAULT 0.00 COMMENT 'Informativo',
  ADD COLUMN `neto_a_pagar` DECIMAL(18,2) NULL COMMENT 'total - total_retenciones, informativo';

CREATE TABLE IF NOT EXISTS `factura_retenciones` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `factura_id` INT NOT NULL,
  `impuesto_id` INT NOT NULL,
  `tributo_codigo` VARCHAR(5) NOT NULL COMMENT '05, 06 o 07',
  `nombre` VARCHAR(150) NOT NULL,
  `aplica_a` ENUM('PRODUCTO','SERVICIO','TODOS') NOT NULL,
  `ciudadesmiggo_id` BIGINT UNSIGNED NULL,
  `subtotal_grupo` DECIMAL(18,2) NOT NULL COMMENT 'Usado para evaluar la base mínima',
  `base` DECIMAL(18,2) NOT NULL COMMENT 'Subtotal o IVA del grupo',
  `tipo_valor` ENUM('PORCENTAJE','POR_MIL') NOT NULL,
  `tarifa` DECIMAL(10,4) NOT NULL,
  `base_minima_uvt` DECIMAL(10,2) NOT NULL,
  `valor_uvt` DECIMAL(18,2) NOT NULL,
  `valor` DECIMAL(18,2) NOT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_factura_ret_factura` (`factura_id`),
  KEY `idx_factura_ret_impuesto` (`impuesto_id`),
  CONSTRAINT `fk_factura_ret_factura` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_factura_ret_impuesto` FOREIGN KEY (`impuesto_id`) REFERENCES `impuestos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------- DOWN -----------------------------------
-- DROP TABLE IF EXISTS `factura_retenciones`;
-- ALTER TABLE `facturas` DROP COLUMN `neto_a_pagar`, DROP COLUMN `total_retenciones`;
