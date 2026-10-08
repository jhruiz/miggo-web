-- =====================================================
-- Tabla: reciboscaja
-- Descripción: Recibos de caja / anticipos de clientes
-- =====================================================
CREATE TABLE `reciboscaja` (
  `id`               INT NOT NULL AUTO_INCREMENT,
  `consecutivo`      VARCHAR(30) NOT NULL COMMENT 'Número del recibo ej: RC-0001',
  `prefijo`          VARCHAR(10) NOT NULL DEFAULT '' COMMENT 'Prefijo tomado de resolucionfacturas',
  `cliente_id`       INT NOT NULL COMMENT 'FK → clientes',
  `empresa_id`       INT NOT NULL COMMENT 'FK → empresas',
  `usuario_id`       INT NOT NULL COMMENT 'FK → usuarios (quien lo registra)',
  `valor`            DECIMAL(15,2) NOT NULL COMMENT 'Valor total del recibo',
  `saldo`            DECIMAL(15,2) NOT NULL COMMENT 'Saldo disponible pendiente de aplicar',
  `cuenta_id`        INT NOT NULL COMMENT 'FK → cuentas (donde ingresa el dinero)',
  `tipopago_id`      INT NOT NULL COMMENT 'FK → tipopagos',
  `concepto`         VARCHAR(500) DEFAULT NULL COMMENT 'Observación o motivo del pago',
  `estado`           ENUM('activo','aplicado','anulado') NOT NULL DEFAULT 'activo',
  `resolucionfactura_id` INT DEFAULT NULL COMMENT 'FK → resolucionfacturas (de donde se obtuvo el consecutivo)',
  `created`          DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_empresa`  (`empresa_id`),
  KEY `idx_cliente`  (`cliente_id`),
  KEY `idx_estado`   (`estado`),
  KEY `idx_created`  (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Recibos de caja y anticipos de clientes';

-- =====================================================
-- Modificación tabla abonofacturas
-- Se agrega la FK al recibo de caja que originó el abono
-- =====================================================
ALTER TABLE `abonofacturas`
  ADD COLUMN `recibocaja_id` INT DEFAULT NULL COMMENT 'FK → reciboscaja (si el abono proviene de un recibo/anticipo)';
