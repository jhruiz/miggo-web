<?php $this->layout = 'inicio'; ?>
<?php
// Formatea una tarifa o base: 2.5000 -> "2,5"; 15.0000 -> "15"
$fmt = function ($n) {
    return rtrim(rtrim(number_format((float) $n, 4, ',', '.'), '0'), ',');
};
$aplicaA = array('PRODUCTO' => 'Productos', 'SERVICIO' => 'Servicios', 'TODOS' => 'Todos');
$condiciones = array('TODOS' => '', 'DECLARANTE' => 'Declarante', 'NO_DECLARANTE' => 'No declarante');
$hoy = date('Y-m-d');
?>
<div class="impuestos index">
    <?php echo $this->element('Impuestos/pestanas', array('activa' => 'retenciones')); ?>
    <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '20', 'id' => 'menuvert')); ?>

    <legend><h2><b><?php echo __('Retenciones en ventas'); ?></b></h2></legend>
    <p class="text-muted">
        Retenciones que los clientes le practican a la empresa en las facturas de venta.
        Para cambiar la tarifa o la base mínima de una retención, use <b>Nueva vigencia</b>: así las facturas ya emitidas conservan su cálculo.
    </p>

    <div class="actions" style="margin-bottom: 15px;">
        <?php echo $this->Html->link(__('Nueva retención'), array('action' => 'addretencion'), array('class' => 'btn btn-primary')); ?>
    </div>

    <form method="get" action="<?php echo $this->Html->url(array('action' => 'retenciones')); ?>" class="form-inline" style="margin-bottom: 15px;">
        <div class="form-group">
            <label for="filtroTributo">Tributo</label>
            <select name="tax_id" id="filtroTributo" class="form-control">
                <option value="">Todos</option>
                <?php foreach ($tributos as $id => $nombre): ?>
                    <option value="<?php echo h($id); ?>" <?php echo (string) $filtros['tax_id'] === (string) $id ? 'selected' : ''; ?>><?php echo h($nombre); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="filtroCiudad">Ciudad</label>
            <select name="ciudad" id="filtroCiudad" class="form-control">
                <option value="">Todas</option>
                <?php foreach ($ciudadesFiltro as $id => $nombre): ?>
                    <option value="<?php echo h($id); ?>" <?php echo (string) $filtros['ciudad'] === (string) $id ? 'selected' : ''; ?>><?php echo h($nombre); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="vigentes" value="1" <?php echo $filtros['vigentes'] === '1' ? 'checked' : ''; ?>> Solo vigentes hoy
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <?php echo $this->Html->link(__('Limpiar'), array('action' => 'retenciones'), array('class' => 'btn btn-default')); ?>
    </form>

    <?php if (!$uvt): ?>
        <div class="alert alert-warning">
            Falta configurar el valor de la UVT para el año <?php echo (int) $uvtAnio; ?> (clave <code>uvt_<?php echo (int) $uvtAnio; ?></code> en configuración de datos).
            Sin ese valor no se calculan retenciones en las facturas de ese año.
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Tributo</th>
                    <th>Se aplica a</th>
                    <th>Ciudad</th>
                    <th>Tarifa</th>
                    <th>Base mínima</th>
                    <th>Vigencia</th>
                    <th>Estado</th>
                    <th class="actions">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($retenciones)): ?>
                <tr><td colspan="9" class="text-center text-muted">No hay retenciones configuradas con estos filtros.</td></tr>
            <?php endif; ?>
            <?php foreach ($retenciones as $r):
                $ret = $r['Retencionventa'];
                $id = $ret['id'];
                $esPorMil = $ret['tipo_valor'] === 'POR_MIL';
                $vigenteHoy = (int) $ret['activo'] === 1 && $ret['vigente_desde'] <= $hoy && (empty($ret['vigente_hasta']) || $ret['vigente_hasta'] >= $hoy);
                $baseUvt = (float) $ret['base_minima_uvt'];
            ?>
                <tr>
                    <td>
                        <?php echo h($ret['nombre']); ?>
                        <?php if (!empty($condiciones[$ret['condicion_declarante']])): ?>
                            <br><small class="text-muted"><?php echo h($condiciones[$ret['condicion_declarante']]); ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?php echo h($r['Taxe']['name'] . ' (' . $r['Taxe']['code'] . ')'); ?></td>
                    <td><?php echo h(isset($aplicaA[$ret['aplica_a']]) ? $aplicaA[$ret['aplica_a']] : $ret['aplica_a']); ?></td>
                    <td><?php echo !empty($r['Ciudadesmiggo']['nombre']) ? h($r['Ciudadesmiggo']['nombre']) : '<span class="text-muted">Nacional</span>'; ?></td>
                    <td><?php echo h($fmt($ret['valor']) . ($esPorMil ? ' por mil' : ' %')); ?></td>
                    <td>
                        <?php if ($baseUvt > 0): ?>
                            <?php echo h($fmt($baseUvt)); ?> UVT
                            <?php if ($uvt): ?>
                                <br><small class="text-muted">$<?php echo h(number_format(round($baseUvt * $uvt), 0, ',', '.')); ?> en <?php echo (int) $uvtAnio; ?></small>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-muted">Sin base mínima</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        Desde <?php echo h($ret['vigente_desde']); ?>
                        <br><small class="text-muted"><?php echo !empty($ret['vigente_hasta']) ? 'Hasta ' . h($ret['vigente_hasta']) : 'Sin fecha de fin'; ?></small>
                    </td>
                    <td>
                        <?php if ((int) $ret['activo'] !== 1): ?>
                            <span class="label label-default">Inactiva</span>
                        <?php elseif ($vigenteHoy): ?>
                            <span class="label label-success">Vigente</span>
                        <?php else: ?>
                            <span class="label label-warning">Activa, fuera de vigencia</span>
                        <?php endif; ?>
                        <?php if ($r['enUso']): ?>
                            <br><span class="label label-info" title="Ya se usó en facturas: no se puede borrar ni cambiar su tarifa">Usada en facturas</span>
                        <?php endif; ?>
                    </td>
                    <td class="actions" style="white-space: nowrap;">
                        <?php echo $this->Html->link(__('Editar'), array('action' => 'editretencion', $id), array('class' => 'btn btn-xs btn-default')); ?>
                        <?php echo $this->Html->link(__('Nueva vigencia'), array('action' => 'nuevavigencia', $id), array('class' => 'btn btn-xs btn-default')); ?>
                        <?php echo $this->Form->postLink(
                            (int) $ret['activo'] === 1 ? __('Desactivar') : __('Activar'),
                            array('action' => 'estadoretencion', $id),
                            array('class' => 'btn btn-xs btn-default'),
                            (int) $ret['activo'] === 1 ? __('¿Desactivar la retención %s?', $ret['nombre']) : __('¿Activar la retención %s?', $ret['nombre'])
                        ); ?>
                        <?php if (!$r['enUso']): ?>
                            <?php echo $this->Form->postLink(
                                __('Eliminar'),
                                array('action' => 'eliminarretencion', $id),
                                array('class' => 'btn btn-xs btn-danger'),
                                __('¿Está seguro que desea eliminar la retención %s?', $ret['nombre'])
                            ); ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
