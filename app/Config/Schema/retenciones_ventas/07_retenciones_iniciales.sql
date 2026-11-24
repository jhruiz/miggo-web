-- ============================================================================
-- 07 · Retenciones iniciales en ventas (ReteFuente, ReteIVA, ReteICA Cali)
-- ============================================================================
-- Carga las 8 retenciones de la sección 3.4 del documento para las empresas
-- indicadas en @empresas. Las tarifas son las del documento; la contadora debe
-- confirmarlas. Las de ReteICA quedan INACTIVAS con tarifa 0 hasta que se
-- conozca la tarifa por mil de Cali.
--
-- Es idempotente: si ya existe una fila de la misma combinación
-- (empresa, tributo, aplica_a, ciudad, condición de la empresa), no la duplica.
--
-- Antes de ejecutar: cambiar la lista de empresas de la línea marcada (A).
-- Después de ejecutar: borrar los archivos de app/tmp/cache/models/ de impuestos
-- y app/tmp/cache/persistent/myapp_cake_core_method_cache (no hace falta si no
-- cambió el esquema, solo los datos).
-- ============================================================================

-- (A) Empresas que reciben las retenciones iniciales
SET @empresas = ',30,35,';

SET @t05 = (SELECT `id` FROM `taxes` WHERE `code` = '05' LIMIT 1);
SET @t06 = (SELECT `id` FROM `taxes` WHERE `code` = '06' LIMIT 1);
SET @t07 = (SELECT `id` FROM `taxes` WHERE `code` = '07' LIMIT 1);

-- Cali, Valle del Cauca (DANE 76001). Si no se encuentra, se omiten las filas de ReteICA.
SET @cali = (SELECT `id` FROM `ciudadesmiggos` WHERE `nombre` = 'Cali' AND `departamento_id` = 26 LIMIT 1);

INSERT INTO `impuestos`
  (`empresa_id`, `clase`, `nombre`, `tax_id`, `aplica_a`, `base_calculo`, `ciudadesmiggo_id`,
   `condicion_declarante`, `tipo_valor`, `valor`, `base_minima_uvt`, `cuenta_contable`,
   `vigente_desde`, `vigente_hasta`, `activo`, `created`, `modified`)
SELECT e.`id`, 'RETENCION', s.`nombre`, s.`tax_id`, s.`aplica_a`, s.`base_calculo`, s.`ciudad_id`,
       s.`condicion`, s.`tipo_valor`, s.`valor`, s.`base_uvt`, s.`cuenta`,
       s.`desde`, NULL, s.`activo`, NOW(), NOW()
FROM `empresas` e
JOIN (
  -- ReteFuente (06)
  SELECT 'ReteFuente productos - declarante'    AS nombre, @t06 AS tax_id, 'PRODUCTO' AS aplica_a, 'SUBTOTAL' AS base_calculo, NULL  AS ciudad_id, 'DECLARANTE'    AS condicion, 'PORCENTAJE' AS tipo_valor, '2.5000'  AS valor, 10.00 AS base_uvt, '135515' AS cuenta, '2026-07-01' AS desde, 1 AS activo
  UNION ALL SELECT 'ReteFuente productos - no declarante', @t06, 'PRODUCTO', 'SUBTOTAL', NULL, 'NO_DECLARANTE', 'PORCENTAJE', '3.5000', 10.00, '135515', '2026-07-01', 1
  UNION ALL SELECT 'ReteFuente servicios - declarante',    @t06, 'SERVICIO', 'SUBTOTAL', NULL, 'DECLARANTE',    'PORCENTAJE', '4.0000',  2.00, '135515', '2026-07-01', 1
  UNION ALL SELECT 'ReteFuente servicios - no declarante', @t06, 'SERVICIO', 'SUBTOTAL', NULL, 'NO_DECLARANTE', 'PORCENTAJE', '6.0000',  2.00, '135515', '2026-07-01', 1
  -- ReteIVA (05): se calcula sobre el IVA; la base mínima se evalúa sobre el subtotal del grupo
  UNION ALL SELECT 'ReteIVA productos',                    @t05, 'PRODUCTO', 'IVA',      NULL, 'TODOS',         'PORCENTAJE', '15.0000', 10.00, '135517', '2026-07-01', 1
  UNION ALL SELECT 'ReteIVA servicios',                    @t05, 'SERVICIO', 'IVA',      NULL, 'TODOS',         'PORCENTAJE', '15.0000',  2.00, '135517', '2026-07-01', 1
  -- ReteICA (07) Cali: INACTIVAS hasta confirmar la tarifa por mil
  UNION ALL SELECT 'ReteICA Cali productos',               @t07, 'PRODUCTO', 'SUBTOTAL', @cali, 'TODOS',        'POR_MIL',    '0.0000',   0.00, '135518', '2026-01-01', 0
  UNION ALL SELECT 'ReteICA Cali servicios',               @t07, 'SERVICIO', 'SUBTOTAL', @cali, 'TODOS',        'POR_MIL',    '0.0000',   0.00, '135518', '2026-01-01', 0
) s
WHERE LOCATE(CONCAT(',', e.`id`, ','), @empresas) > 0
  AND s.`tax_id` IS NOT NULL
  AND (s.`tax_id` <> @t07 OR s.`ciudad_id` IS NOT NULL)
  AND NOT EXISTS (
    SELECT 1 FROM `impuestos` i
    WHERE i.`clase` = 'RETENCION'
      AND i.`empresa_id` = e.`id`
      AND i.`tax_id` = s.`tax_id`
      AND i.`aplica_a` = s.`aplica_a`
      AND i.`ciudadesmiggo_id` <=> s.`ciudad_id`
      AND i.`condicion_declarante` = s.`condicion`
  );

-- Verificación: debe listar 8 filas por empresa
SELECT `empresa_id`, `nombre`, `aplica_a`, `condicion_declarante`, `tipo_valor`, `valor`, `base_minima_uvt`, `vigente_desde`, `activo`
FROM `impuestos`
WHERE `clase` = 'RETENCION' AND LOCATE(CONCAT(',', `empresa_id`, ','), @empresas) > 0
ORDER BY `empresa_id`, `tax_id`, `aplica_a`, `condicion_declarante`;

-- ============================================================================
-- DOWN: borra solo las filas sembradas que no se han usado en facturas
-- ============================================================================
-- DELETE i FROM `impuestos` i
-- WHERE i.`clase` = 'RETENCION'
--   AND LOCATE(CONCAT(',', i.`empresa_id`, ','), @empresas) > 0
--   AND i.`nombre` IN ('ReteFuente productos - declarante','ReteFuente productos - no declarante',
--                      'ReteFuente servicios - declarante','ReteFuente servicios - no declarante',
--                      'ReteIVA productos','ReteIVA servicios','ReteICA Cali productos','ReteICA Cali servicios')
--   AND NOT EXISTS (SELECT 1 FROM `factura_retenciones` fr WHERE fr.`impuesto_id` = i.`id`);
