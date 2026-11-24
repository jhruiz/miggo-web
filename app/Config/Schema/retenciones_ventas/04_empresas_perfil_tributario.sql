-- =====================================================================
-- RETENCIONES EN VENTAS - Migración 04: perfil tributario de la empresa
-- Documento: secciones 3.6 y 5.3 (adaptadas)
--
-- La "empresa" es la que VENDE y a la que le practican retenciones. En el modelo
-- multi-sede (centros de costo) cada sede es una empresa distinta, así que cada
-- una tiene su propio perfil.
--
-- Decisiones de adaptación:
--  * No hay interruptor "retenciones habilitadas". Una empresa participa solo si
--    tiene filas activas de clase RETENCION en `impuestos` (se siembran solo en las
--    empresas del piloto) y el cliente tiene marcadas las casillas practica_*.
--    Para apagar el cálculo en una empresa basta desactivar sus filas de retención.
--  * La tabla pivote usa la convención CakePHP (empresas_tiposresponsabilidades)
--    y el nombre de columna que ya usa `clientes` (tiposresponsabilidade_id).
--  * Tipos alineados con las tablas reales: empresas.id es INT y
--    tiposresponsabilidades.id es BIGINT UNSIGNED. Ambas son InnoDB, la FK es válida.
--
-- No es idempotente (ALTER). Ejecutar una sola vez.
-- =====================================================================
SET NAMES utf8mb4;

-- ----------------------------- UP ------------------------------------
ALTER TABLE `empresas`
  ADD COLUMN `tipo_persona` ENUM('NATURAL','JURIDICA') NOT NULL DEFAULT 'JURIDICA',
  ADD COLUMN `declarante_renta` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Decide las filas de ReteFuente (condicion_declarante)';

CREATE TABLE IF NOT EXISTS `empresas_tiposresponsabilidades` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `empresa_id` INT NOT NULL,
  `tiposresponsabilidade_id` BIGINT UNSIGNED NOT NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_empresa_tiposresp` (`empresa_id`, `tiposresponsabilidade_id`),
  KEY `idx_emp_tiposresp_tipo` (`tiposresponsabilidade_id`),
  CONSTRAINT `fk_emp_tiposresp_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_emp_tiposresp_tipo` FOREIGN KEY (`tiposresponsabilidade_id`) REFERENCES `tiposresponsabilidades` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------- DOWN -----------------------------------
-- DROP TABLE IF EXISTS `empresas_tiposresponsabilidades`;
-- ALTER TABLE `empresas`
--   DROP COLUMN `declarante_renta`, DROP COLUMN `tipo_persona`;
