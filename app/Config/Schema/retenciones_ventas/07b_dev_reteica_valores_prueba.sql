-- ============================================================================
-- 07b · SOLO DESARROLLO · Valores de prueba para ReteICA Cali
-- ============================================================================
-- NO ejecutar en producción. Ahí cada contador define la tarifa por mil de su
-- municipio desde Impuestos > Retenciones (Editar o Nueva vigencia).
--
-- Deja activas las dos filas de ReteICA Cali sembradas por 07 para poder probar
-- la facturación con el mismo comportamiento que tendrá en producción.
-- Los valores NO son oficiales.
-- ============================================================================

SET @empresas = ',30,35,';

UPDATE `impuestos`
SET `valor` = '4.1400', `activo` = 1, `modified` = NOW()
WHERE `clase` = 'RETENCION' AND `nombre` = 'ReteICA Cali productos'
  AND LOCATE(CONCAT(',', `empresa_id`, ','), @empresas) > 0 and id > 0;

UPDATE `impuestos`
SET `valor` = '9.6600', `activo` = 1, `modified` = NOW()
WHERE `clase` = 'RETENCION' AND `nombre` = 'ReteICA Cali servicios'
  AND LOCATE(CONCAT(',', `empresa_id`, ','), @empresas) > 0 and id > 0;

-- DOWN
-- UPDATE `impuestos` SET `valor` = '0.0000', `activo` = 0, `modified` = NOW()
-- WHERE `clase` = 'RETENCION' AND `nombre` IN ('ReteICA Cali productos','ReteICA Cali servicios')
--   AND LOCATE(CONCAT(',', `empresa_id`, ','), @empresas) > 0;
