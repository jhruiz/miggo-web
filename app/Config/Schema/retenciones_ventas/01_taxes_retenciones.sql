-- =====================================================================
-- RETENCIONES EN VENTAS - Migración 01: tributos DIAN de retención
-- Documento: "Requerimientos técnicos – Retenciones en ventas (MIGGO)", sección 3.1
--
-- La tabla `taxes` ya existe (hoy solo tiene IVA 01, INC 04 e INC Bolsas 22).
-- Solo se agregan los tres tributos de retención. No hay UPDATE de códigos
-- 08/30 porque esos códigos no existen en esta base.
--
-- Ejecutar contra miggoweb_nc con charset utf8mb4.
-- Es idempotente: se puede ejecutar más de una vez sin duplicar.
-- =====================================================================
SET NAMES utf8mb4;

-- ----------------------------- UP ------------------------------------
INSERT INTO `taxes` (`name`, `description`, `code`, `created_at`, `updated_at`)
SELECT 'ReteIVA', 'Retención sobre el IVA', '05', NOW(), NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `taxes` WHERE `code` = '05');

INSERT INTO `taxes` (`name`, `description`, `code`, `created_at`, `updated_at`)
SELECT 'ReteRenta', 'Retención sobre Renta', '06', NOW(), NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `taxes` WHERE `code` = '06');

INSERT INTO `taxes` (`name`, `description`, `code`, `created_at`, `updated_at`)
SELECT 'ReteICA', 'Retención sobre el ICA', '07', NOW(), NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `taxes` WHERE `code` = '07');

-- ---------------------------- DOWN -----------------------------------
-- (ejecutar manualmente; no borra tributos que ya estén en uso)
-- DELETE t FROM `taxes` t
-- WHERE t.`code` IN ('05','06','07')
--   AND NOT EXISTS (SELECT 1 FROM `impuestos` i WHERE i.`tax_id` = t.`id`);
