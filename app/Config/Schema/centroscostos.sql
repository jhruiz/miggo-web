-- =====================================================
-- Tabla: centroscostos
-- Descripción: Centros de costo por empresa
--
-- Mapeo desde la especificación (inglés → español):
--   name        → nombre
--   company_id  → empresa_id
--   is_editable → editable
-- =====================================================
CREATE TABLE `centroscostos` (
  `id`          INT NOT NULL AUTO_INCREMENT,
  `nombre`      VARCHAR(150) NOT NULL COMMENT 'Nombre del centro de costo',
  `empresa_id`  INT NOT NULL COMMENT 'FK → empresas',
  `editable`    TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1 = se puede modificar, 0 = fijo del sistema',
  `deleted`     TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Soft delete: 1 = eliminado',
  `created`     DATETIME DEFAULT NULL,
  `modified`    DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_empresa` (`empresa_id`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Centros de costo por empresa';

-- =====================================================
-- Tabla pivote: centroscostos_usuarios
-- Relación muchos-a-muchos entre centros de costo y usuarios.
-- Un usuario puede pertenecer a varios centros de costo de distintas empresas.
-- Convención CakePHP 2.x: nombre de tabla en orden alfabético con guion bajo.
-- =====================================================
CREATE TABLE `centroscostos_usuarios` (
  `id`              INT NOT NULL AUTO_INCREMENT,
  `centrocosto_id`  INT NOT NULL COMMENT 'FK → centroscostos',
  `usuario_id`      INT NOT NULL COMMENT 'FK → usuarios',
  `created`         DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_centro_usuario` (`centrocosto_id`, `usuario_id`),
  KEY `idx_centro`  (`centrocosto_id`),
  KEY `idx_usuario` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Pivote centros de costo ↔ usuarios';
