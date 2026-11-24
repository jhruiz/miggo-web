-- =====================================================================
-- RETENCIONES EN VENTAS - Migración 05: datos tributarios del cliente
-- Documento: secciones 3.7 y 5.4 (adaptadas)
--
-- Estado real de `clientes` (36.046 filas): ya tiene tiposresponsabilidade_id
-- (una sola, hoy NULL en todos), tiposorganizacione_id (hoy NULL en todos),
-- ciudade_id y ciudadesmiggo_id. No hay nada que migrar a la tabla pivote.
--
-- Decisiones:
--  * Se CONSERVA clientes.tiposresponsabilidade_id (lo puede leer la facturación
--    electrónica). Las responsabilidades múltiples van en la tabla pivote.
--  * No se crea `ciudad_ica_id`: el municipio para ReteICA es la ciudad del cliente
--    (`clientes.ciudadesmiggo_id`). El motor la compara con impuestos.ciudadesmiggo_id.
--  * `tipo_persona` incluye ENTIDAD_PUBLICA, que no existe en tiposorganizaciones
--    (ver decisión pendiente).
--  * Las tres casillas practica_* quedan en 0: nada cambia para los clientes existentes.
--  * Pivote con convención CakePHP: clientes_tiposresponsabilidades.
--
-- No es idempotente (ALTER). Ejecutar una sola vez.
-- ALTER sobre ~36 mil filas: rápido, pero conviene ejecutarlo fuera de horario en producción.
-- =====================================================================
SET NAMES utf8mb4;

-- ----------------------------- UP ------------------------------------
ALTER TABLE `clientes`
  ADD COLUMN `dv` TINYINT UNSIGNED NULL COMMENT 'Dígito de verificación, calculado' AFTER `nit`,
  ADD COLUMN `regimene_id` INT NULL COMMENT 'Misma lista que proveedores (regimenes)',
  ADD COLUMN `tipo_persona` ENUM('NATURAL','JURIDICA','ENTIDAD_PUBLICA') NULL,
  ADD COLUMN `practica_retefuente` TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN `practica_reteiva` TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN `practica_reteica` TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN `datos_tributarios_revisados` TINYINT(1) NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS `clientes_tiposresponsabilidades` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `cliente_id` INT NOT NULL,
  `tiposresponsabilidade_id` BIGINT UNSIGNED NOT NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cliente_tiposresp` (`cliente_id`, `tiposresponsabilidade_id`),
  KEY `idx_cli_tiposresp_tipo` (`tiposresponsabilidade_id`),
  CONSTRAINT `fk_cli_tiposresp_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cli_tiposresp_tipo` FOREIGN KEY (`tiposresponsabilidade_id`) REFERENCES `tiposresponsabilidades` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------- DOWN -----------------------------------
-- DROP TABLE IF EXISTS `clientes_tiposresponsabilidades`;
-- ALTER TABLE `clientes`
--   DROP COLUMN `datos_tributarios_revisados`,
--   DROP COLUMN `practica_reteica`, DROP COLUMN `practica_reteiva`, DROP COLUMN `practica_retefuente`,
--   DROP COLUMN `tipo_persona`, DROP COLUMN `regimene_id`, DROP COLUMN `dv`;
