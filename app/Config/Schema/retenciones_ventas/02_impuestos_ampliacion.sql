-- =====================================================================
-- RETENCIONES EN VENTAS - Migración 02: ampliación de la tabla `impuestos`
-- Documento: sección 3.3 (adaptada a la tabla real)
--
-- Tabla real: id, valor (varchar 11), empresa_id, valoprc (1=porcentaje, 0=valor), tax_id
-- Cada fila de clase RETENCION es una regla completa: tributo (tax_id), tipo de
-- ítem, ciudad (ReteICA), condición de la empresa, tarifa, base mínima y vigencia.
--
-- Decisiones de adaptación:
--  * Las filas existentes quedan como clase IMPUESTO y no cambian de comportamiento.
--  * `valor` (varchar 11) se conserva: la tarifa de una retención ("15.0000") cabe.
--    El motor debe convertirla a número.
--  * `valoprc` se conserva para los impuestos. `tipo_valor` SOLO se usa cuando
--    clase = RETENCION (permite POR_MIL). Para IMPUESTO queda NULL.
--  * La ciudad usa `ciudadesmiggo_id` BIGINT UNSIGNED, igual que ciudadesmiggos.id.
--  * Sin FOREIGN KEY: tax_id es INT y taxes.id es BIGINT UNSIGNED, la FK no se puede crear.
--  * `nombre` y los textos nuevos van en utf8mb4 (la tabla es latin1).
--
-- !! ANTES de insertar cualquier fila clase RETENCION (migración de datos iniciales),
-- !! el modelo Impuesto y la pantalla /impuestos deben filtrar clase = 'IMPUESTO';
-- !! de lo contrario las retenciones aparecerían en las listas de impuestos de productos.
--
-- Nota operativa: ALTER sobre una tabla InnoDB pequeña (38 filas hoy).
-- No es idempotente (MySQL no soporta ADD COLUMN IF NOT EXISTS).
-- =====================================================================

-- ----------------------------- UP ------------------------------------
ALTER TABLE `impuestos`
  ADD COLUMN `clase` ENUM('IMPUESTO','RETENCION') NOT NULL DEFAULT 'IMPUESTO' AFTER `empresa_id`,
  ADD COLUMN `nombre` VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'Nombre de la regla (clase RETENCION)' AFTER `clase`,
  ADD COLUMN `aplica_a` ENUM('PRODUCTO','SERVICIO','TODOS') NOT NULL DEFAULT 'TODOS',
  ADD COLUMN `base_calculo` ENUM('SUBTOTAL','IVA') NOT NULL DEFAULT 'SUBTOTAL',
  ADD COLUMN `tipo_valor` ENUM('PORCENTAJE','VALOR','POR_MIL') NULL COMMENT 'Solo clase RETENCION; en IMPUESTO se usa valoprc',
  ADD COLUMN `ciudadesmiggo_id` BIGINT UNSIGNED NULL COMMENT 'Obligatorio solo para ReteICA (07)',
  ADD COLUMN `condicion_declarante` ENUM('TODOS','DECLARANTE','NO_DECLARANTE') NOT NULL DEFAULT 'TODOS' COMMENT 'Condición de la EMPRESA que vende',
  ADD COLUMN `base_minima_uvt` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Se evalúa sobre el subtotal del grupo',
  ADD COLUMN `cuenta_contable` VARCHAR(20) NULL COMMENT 'Cuenta PUC para TRIUCO',
  ADD COLUMN `vigente_desde` DATE NULL,
  ADD COLUMN `vigente_hasta` DATE NULL,
  ADD COLUMN `activo` TINYINT(1) NOT NULL DEFAULT 1,
  ADD COLUMN `created` DATETIME NULL,
  ADD COLUMN `modified` DATETIME NULL,
  ADD INDEX `idx_impuestos_motor` (`empresa_id`, `clase`, `activo`, `tax_id`, `aplica_a`, `ciudadesmiggo_id`, `vigente_desde`);

-- ---------------------------- DOWN -----------------------------------
-- (ejecutar manualmente)
-- DELETE FROM `impuestos` WHERE `clase` = 'RETENCION';
-- ALTER TABLE `impuestos`
--   DROP INDEX `idx_impuestos_motor`,
--   DROP COLUMN `modified`, DROP COLUMN `created`, DROP COLUMN `activo`,
--   DROP COLUMN `vigente_hasta`, DROP COLUMN `vigente_desde`, DROP COLUMN `cuenta_contable`,
--   DROP COLUMN `base_minima_uvt`, DROP COLUMN `condicion_declarante`, DROP COLUMN `ciudadesmiggo_id`,
--   DROP COLUMN `tipo_valor`, DROP COLUMN `base_calculo`, DROP COLUMN `aplica_a`,
--   DROP COLUMN `nombre`, DROP COLUMN `clase`;
