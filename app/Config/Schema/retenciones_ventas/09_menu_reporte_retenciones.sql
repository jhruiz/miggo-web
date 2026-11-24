-- =====================================================================
-- RETENCIONES EN VENTAS - Migración 09: menú "Reportes de retenciones" (paso 7)
--
-- Crea la opción de menú en el mismo grupo de los reportes de facturación
-- (menu_principal 8) y se la asigna a los MISMOS perfiles que hoy ven el reporte
-- "FACTURAS - CLIENTES" (cloudmenus.id = 64).
--
-- Idempotente: no duplica la opción ni las asignaciones si ya existen.
-- =====================================================================
SET NAMES utf8mb4;

-- ----------------------------- UP ------------------------------------
INSERT INTO `cloudmenus` (`descripcion`, `url`, `imagen`, `orden`, `administrador`, `menu_principal`, `ayuda`)
SELECT 'REPORTE DE RETENCIONES', '/reporteretenciones', 'lgM_UTILIDAD POR VENTAS.png', 58, 0, 8,
       'Retenciones que los clientes le practicaron a la empresa, certificados pendientes, resumen del periodo y diferencias'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `cloudmenus` WHERE `url` = '/reporteretenciones');

SET @menu_ret = (SELECT `id` FROM `cloudmenus` WHERE `url` = '/reporteretenciones' LIMIT 1);

INSERT INTO `cloudmenus_perfiles` (`cloudmenu_id`, `perfile_id`)
SELECT DISTINCT @menu_ret, cp.`perfile_id`
FROM `cloudmenus_perfiles` cp
WHERE cp.`cloudmenu_id` = 64
  AND NOT EXISTS (
    SELECT 1 FROM `cloudmenus_perfiles` x
    WHERE x.`cloudmenu_id` = @menu_ret AND x.`perfile_id` = cp.`perfile_id`
  );

-- Verificación
SELECT `id`, `descripcion`, `url`, `menu_principal` FROM `cloudmenus` WHERE `id` = @menu_ret;
SELECT COUNT(*) AS perfiles_asignados FROM `cloudmenus_perfiles` WHERE `cloudmenu_id` = @menu_ret;

-- ---------------------------- DOWN -----------------------------------
-- SET @menu_ret = (SELECT `id` FROM `cloudmenus` WHERE `url` = '/reporteretenciones' LIMIT 1);
-- DELETE FROM `cloudmenus_perfiles` WHERE `cloudmenu_id` = @menu_ret;
-- DELETE FROM `cloudmenus` WHERE `id` = @menu_ret;
