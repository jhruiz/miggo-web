-- =====================================================================
-- RETENCIONES EN VENTAS - Migración 03: valor de la UVT por año
-- Documento: sección 3.5 (simplificada por decisión del proyecto)
--
-- En lugar de la tabla `valores_uvt` y de la tabla `configuraciones`, se usa la
-- tabla clave-valor `configuraciondatos` con una clave por año: uvt_AAAA.
-- El motor busca "uvt_" + año de la fecha de la factura. Si la clave no existe,
-- no calcula retenciones y avisa "Falta configurar el valor de la UVT para el año AAAA".
-- Nunca debe asumir la UVT de otro año.
-- El valor usado queda guardado en factura_retenciones.valor_uvt (snapshot), por lo
-- que un cambio posterior de UVT no altera facturas ya emitidas.
--
-- OJO para el motor: Configuraciondato::obtenerValorDatoConfig() lanza un error si la
-- clave no existe (lee $infoDatoConfig[0] sin validar). El motor debe usar una
-- consulta segura que devuelva vacío cuando falte la clave.
--
-- Cada año nuevo: INSERT de la clave uvt_AAAA (en diciembre).
-- Es idempotente.
-- =====================================================================
SET NAMES utf8mb4;

-- ----------------------------- UP ------------------------------------
INSERT INTO `configuraciondatos` (`nombre`, `valor`)
SELECT 'uvt_2025', '49799' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `configuraciondatos` WHERE `nombre` = 'uvt_2025');

INSERT INTO `configuraciondatos` (`nombre`, `valor`)
SELECT 'uvt_2026', '52374' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `configuraciondatos` WHERE `nombre` = 'uvt_2026');

-- ---------------------------- DOWN -----------------------------------
-- DELETE FROM `configuraciondatos` WHERE `nombre` IN ('uvt_2025', 'uvt_2026');
