-- Responsabilidades fiscales que usa el motor de retenciones.
-- Debe ejecutarse ANTES de 04_empresas_perfil_tributario.sql y 05_clientes_datos_tributarios.sql:
-- las tablas pivote de esos scripts tienen FK hacia esta tabla.
--
-- En el entorno de desarrollo la tabla ya existia (misma estructura que usa APIDIAN), por eso
-- los scripts 04 y 05 no la creaban. En un servidor donde no exista, la FK falla con
-- "Error 1005 errno 150".
--
-- Los ids (7, 9, 14, 112, 117) y los codigos deben conservarse: el motor consulta por codigo
-- (O-13, O-15, O-47, R-99-PN) y los ids coinciden con los de APIDIAN.
--
-- Idempotente: se puede ejecutar mas de una vez.

CREATE TABLE IF NOT EXISTS `tiposresponsabilidades` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo` CHAR(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET NAMES utf8mb4;

INSERT IGNORE INTO `tiposresponsabilidades` (`id`, `descripcion`, `codigo`, `created_at`, `updated_at`) VALUES
(7,   'Gran contribuyente',                                         'O-13',    NOW(), NOW()),
(9,   'Autorretenedor',                                              'O-15',    NOW(), NOW()),
(14,  'Agente de retención en el impuesto sobre las ventas',         'O-23',    NOW(), NOW()),
(112, 'Régimen Simple de Tributación – SIMPLE',                      'O-47',    NOW(), NOW()),
(117, 'No responsable',                                              'R-99-PN', NOW(), NOW());

-- ---------------------------- DOWN -----------------------------------
-- Solo si la tabla fue creada por este script y nada la referencia:
-- DROP TABLE IF EXISTS `tiposresponsabilidades`;
