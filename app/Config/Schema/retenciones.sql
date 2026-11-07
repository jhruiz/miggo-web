-- =====================================================
-- Tabla: retenciones
-- Descripción: Retenciones de la empresa (retefuente, reteica, reteiva, etc.)
--
-- Mapeo desde el API (inglés → español):
--   name                  → nombre
--   company_id            → empresa_id
--   percentage            → porcentaje
--   is_active             → activo
--   withholdings_category → categoriaretencion_id
--   account_id_v          → cuentaventa_id   (cuenta contable de ventas)
--   account_id_c          → cuentacompra_id  (cuenta contable de compras)
--   type_rate             → tipotarifa_id
-- =====================================================
CREATE TABLE `retenciones` (
  `id`                     INT NOT NULL AUTO_INCREMENT,
  `nombre`                 VARCHAR(100) NOT NULL COMMENT 'Nombre de la retención ej: Retefuente 2.5%',
  `empresa_id`             INT NOT NULL COMMENT 'FK → empresas',
  `porcentaje`             DECIMAL(6,3) NOT NULL DEFAULT 0 COMMENT 'Porcentaje de la retención ej: 2.500',
  `activo`                 TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1 = activa, 0 = inactiva',
  `categoriaretencion_id`  INT NOT NULL COMMENT 'Categoría de la retención (fuente, iva, ica)',
  `cuentaventa_id`         INT NOT NULL COMMENT 'FK → cuentas (contrapartida en ventas)',
  `cuentacompra_id`        INT NOT NULL COMMENT 'FK → cuentas (contrapartida en compras)',
  `tipotarifa_id`          INT NOT NULL COMMENT 'Tipo de tarifa (porcentaje, valor fijo)',
  `base_minima_uvt`        DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'Tope mínimo en UVT a partir del cual aplica la retención; 0 = sin mínimo',
  `deleted`                TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Soft delete: 1 = eliminada',
  `created`                DATETIME DEFAULT NULL,
  `modified`               DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_empresa`   (`empresa_id`),
  KEY `idx_activo`    (`activo`),
  KEY `idx_deleted`   (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Retenciones configuradas por empresa';

-- =====================================================
-- Comportamiento de cada categoría de retención (referencia)
-- Esto NO se guarda como columnas: el modelo Retencione lo deriva
-- de categoriaretencion_id mediante su mapa estático $comportamiento.
--
--   categoriaretencion_id | base_calculo | unidad_tarifa
--   ----------------------|--------------|---------------
--   1  Retención fuente   | subtotal     | porcentaje
--   2  ReteIVA            | iva          | porcentaje
--   3  ReteICA            | subtotal     | pormil
-- =====================================================

-- =====================================================
-- UVT vigente (se almacena en la tabla genérica configuraciondatos).
-- Actualizar este valor cada año. Ejemplo con la UVT 2025.
-- =====================================================
INSERT INTO `configuraciondatos` (`nombre`, `valor`)
VALUES ('uvt_vigente', '49799');
