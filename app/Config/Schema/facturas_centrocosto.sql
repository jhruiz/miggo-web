-- =====================================================
-- FASE 1 — Centro de costo en facturación
--
-- Agrega la referencia al centro de costo (que representa la sede/empresa
-- destino) en las tablas de facturas y prefacturas.
--
-- En esta fase SOLO se agregan las columnas. Ningún flujo las usa todavía,
-- por lo que este cambio no altera el comportamiento actual del sistema.
--
-- El centrocosto_id es NULL por defecto: las facturas/prefacturas existentes
-- y las que se creen hasta implementar las fases siguientes quedan sin centro
-- de costo, operando como hasta ahora (empresa del usuario logueado).
-- =====================================================

ALTER TABLE `facturas`
  ADD COLUMN `centrocosto_id` INT DEFAULT NULL
  COMMENT 'FK → centroscostos (sede/empresa a la que corresponde la factura). NULL = empresa del usuario';

ALTER TABLE `prefacturas`
  ADD COLUMN `centrocosto_id` INT DEFAULT NULL
  COMMENT 'FK → centroscostos (sede/empresa a la que corresponde la prefactura). NULL = empresa del usuario';

-- Índices para acelerar los reportes/filtros por centro de costo
ALTER TABLE `facturas`    ADD KEY `idx_centrocosto` (`centrocosto_id`);
ALTER TABLE `prefacturas` ADD KEY `idx_centrocosto` (`centrocosto_id`);
