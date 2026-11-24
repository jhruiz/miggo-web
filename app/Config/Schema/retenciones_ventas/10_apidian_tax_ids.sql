-- Paso 8: ids de los tributos de retencion en la tabla global `taxes` de APIDIAN.
-- DIAN 05 ReteIVA -> 5, 06 ReteRenta -> 6, 07 ReteICA -> 7 (confirmado en produccion APIDIAN).
-- Idempotente: no duplica si ya existen.

INSERT INTO configuraciondatos (nombre, valor)
SELECT 'apidian_tax_05', '5' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM configuraciondatos WHERE nombre = 'apidian_tax_05');

INSERT INTO configuraciondatos (nombre, valor)
SELECT 'apidian_tax_06', '6' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM configuraciondatos WHERE nombre = 'apidian_tax_06');

INSERT INTO configuraciondatos (nombre, valor)
SELECT 'apidian_tax_07', '7' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM configuraciondatos WHERE nombre = 'apidian_tax_07');
