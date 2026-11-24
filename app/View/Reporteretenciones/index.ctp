<?php $this->layout = 'inicio'; ?>
<?php
$fmt = function ($v) {
    return '$ ' . number_format((float) $v, 2);
};
$url = function (array $cambios) use ($queryActual) {
    return array('controller' => 'reporteretenciones', 'action' => 'index', '?' => array_merge($queryActual, $cambios));
};
?>
<div class="reporteretenciones index container-fluid" style="padding: 20px;">
    <legend><h2><b><?php echo __('Reportes de retenciones'); ?></b></h2></legend>

    <ul class="nav nav-tabs" style="margin-bottom: 15px;">
        <?php foreach ($reportes as $clave => $nombre): ?>
            <li class="<?php echo $clave === $reporte ? 'active' : ''; ?>">
                <?php echo $this->Html->link($nombre, $url(array('reporte' => $clave))); ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <form method="get" action="<?php echo $this->Html->url(array('controller' => 'reporteretenciones', 'action' => 'index')); ?>" class="form-inline" style="margin-bottom: 15px;">
        <input type="hidden" name="reporte" value="<?php echo h($reporte); ?>">
        <div class="form-group">
            <label><?php echo $reporte === 'diferencias' ? __('Facturas desde') : __('Desde'); ?></label>
            <input type="date" name="desde" class="form-control" value="<?php echo h($filtros['desde']); ?>">
        </div>
        <div class="form-group">
            <label><?php echo __('Hasta'); ?></label>
            <input type="date" name="hasta" class="form-control" value="<?php echo h($filtros['hasta']); ?>">
        </div>
        <div class="form-group">
            <label><?php echo __('Cliente'); ?></label>
            <input type="text" name="cliente" class="form-control" maxlength="100" placeholder="NIT o nombre" value="<?php echo h($filtros['cliente']); ?>">
        </div>
        <div class="form-group">
            <label><?php echo __('Tipo'); ?></label>
            <select name="tributo" class="form-control">
                <option value="">Todos</option>
                <?php foreach ($tributos as $codigo => $nombre): ?>
                    <option value="<?php echo h($codigo); ?>" <?php echo $filtros['tributo'] === (string) $codigo ? 'selected' : ''; ?>><?php echo h($nombre); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if ($reporte === 'detalle' || $reporte === 'resumen'): ?>
            <div class="form-group">
                <label><?php echo __('Certificado'); ?></label>
                <select name="certificado" class="form-control">
                    <option value="">Todos</option>
                    <option value="1" <?php echo $filtros['certificado'] === '1' ? 'selected' : ''; ?>>Recibido</option>
                    <option value="0" <?php echo $filtros['certificado'] === '0' ? 'selected' : ''; ?>>Pendiente</option>
                </select>
            </div>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary"><?php echo __('Buscar'); ?></button>
        <?php echo $this->Html->link('<i class="fa fa-file-excel-o"></i> ' . __('Descargar Excel'),
            array('controller' => 'reporteretenciones', 'action' => 'exportar', '?' => $queryActual),
            array('class' => 'btn btn-success', 'escape' => false)); ?>
    </form>

    <?php if ($reporte === 'detalle'): ?>
        <p class="text-muted"><?php echo __('Retenciones que los clientes le practicaron a la empresa (aplicadas al facturar o al pagar la cuenta por cobrar). Para registrar un certificado, marque las retenciones que cubre y adjunte el archivo.'); ?></p>
        <form method="post" enctype="multipart/form-data" action="<?php echo $this->Html->url(array('controller' => 'reporteretenciones', 'action' => 'registrarcertificado')); ?>">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="marcarTodas" title="Marcar todas"></th>
                            <th>Fecha</th><th>Origen</th><th>NIT</th><th>Cliente</th><th>Factura</th><th>Retención</th>
                            <th class="text-right">Base</th><th>Tarifa</th><th class="text-right">Valor</th><th>Certificado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($datos['filas'])): ?>
                            <tr><td colspan="11" class="text-center text-muted">No hay retenciones en el período.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($datos['filas'] as $f): ?>
                            <tr>
                                <td><input type="checkbox" class="marcaRetencion" name="data[Certificado][ids][]" value="<?php echo (int) $f['id']; ?>"></td>
                                <td><?php echo h($f['fecha']); ?></td>
                                <td><?php echo h($f['origen']); ?></td>
                                <td><?php echo h($f['nit']); ?></td>
                                <td><?php echo h($f['cliente']); ?></td>
                                <td><?php echo $this->Html->link($f['factura'], array('controller' => 'facturas', 'action' => 'view', $f['factura_id'])); ?></td>
                                <td><?php echo h($f['retencion']); ?></td>
                                <td class="text-right"><?php echo h($fmt($f['base'])); ?></td>
                                <td><?php echo h($f['tarifa']); ?></td>
                                <td class="text-right"><?php echo h($fmt($f['valor'])); ?></td>
                                <td>
                                    <?php if ($f['certificado']): ?>
                                        <span class="label label-success">Sí</span> <?php echo h($f['certificado_fecha']); ?>
                                        <?php if ($f['tiene_archivo']): ?>
                                            <?php echo $this->Html->link('Ver', array('controller' => 'reporteretenciones', 'action' => 'vercertificado', $f['id']), array('target' => '_blank')); ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="label label-warning">Pendiente</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <?php if (!empty($datos['filas'])): ?>
                        <tfoot>
                            <tr>
                                <th colspan="7" class="text-right">Total</th>
                                <th class="text-right"><?php echo h($fmt($datos['total_base'])); ?></th>
                                <th></th>
                                <th class="text-right"><?php echo h($fmt($datos['total_valor'])); ?></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    <?php endif; ?>
                </table>
            </div>

            <?php if (!empty($datos['filas'])): ?>
                <div class="well well-sm form-inline">
                    <b><?php echo __('Certificado de las retenciones marcadas'); ?>:</b>
                    <div class="form-group">
                        <label><?php echo __('Fecha'); ?></label>
                        <input type="date" name="data[Certificado][fecha]" class="form-control" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label><?php echo __('Archivo'); ?></label>
                        <input type="file" name="data[Certificado][archivo]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                    <button type="submit" class="btn btn-primary"><?php echo __('Registrar certificado'); ?></button>
                    <button type="submit" class="btn btn-default" formaction="<?php echo $this->Html->url(array('controller' => 'reporteretenciones', 'action' => 'quitarcertificado')); ?>"
                            onclick="return confirm('¿Quitar el certificado de las retenciones marcadas?');"><?php echo __('Quitar certificado'); ?></button>
                    <small class="text-muted"><?php echo __('PDF, JPG o PNG, máximo 5 MB.'); ?></small>
                </div>
            <?php endif; ?>
        </form>
        <script>
            document.getElementById('marcarTodas').addEventListener('change', function () {
                var marcas = document.querySelectorAll('.marcaRetencion');
                for (var i = 0; i < marcas.length; i++) { marcas[i].checked = this.checked; }
            });
        </script>

    <?php elseif ($reporte === 'pendientes'): ?>
        <p class="text-muted"><?php echo __('Clientes a los que hay que pedirles el certificado de retención.'); ?></p>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr><th>NIT</th><th>Cliente</th><th class="text-right">Retenciones sin certificado</th><th class="text-right">Valor total</th><th>Más antigua</th></tr>
            </thead>
            <tbody>
                <?php if (empty($datos['filas'])): ?>
                    <tr><td colspan="5" class="text-center text-muted">No hay certificados pendientes en el período.</td></tr>
                <?php endif; ?>
                <?php foreach ($datos['filas'] as $f): ?>
                    <tr>
                        <td><?php echo h($f['nit']); ?></td>
                        <td><?php echo $this->Html->link($f['cliente'], $url(array('reporte' => 'detalle', 'cliente' => (string) $f['nit'], 'certificado' => '0'))); ?></td>
                        <td class="text-right"><?php echo (int) $f['cantidad']; ?></td>
                        <td class="text-right"><?php echo h($fmt($f['valor'])); ?></td>
                        <td><?php echo h($f['mas_antigua']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <?php if (!empty($datos['filas'])): ?>
                <tfoot><tr><th colspan="3" class="text-right">Total</th><th class="text-right"><?php echo h($fmt($datos['total_valor'])); ?></th><th></th></tr></tfoot>
            <?php endif; ?>
        </table>

    <?php elseif ($reporte === 'resumen'): ?>
        <p class="text-muted"><?php echo __('Valor retenido a favor de la empresa en el período, por tipo.'); ?></p>
        <table class="table table-striped table-bordered table-condensed" style="max-width: 700px;">
            <thead><tr><th>Tipo</th><th class="text-right">Cantidad</th><th class="text-right">Base</th><th class="text-right">Valor</th></tr></thead>
            <tbody>
                <?php if (empty($datos['tributos'])): ?>
                    <tr><td colspan="4" class="text-center text-muted">No hay retenciones en el período.</td></tr>
                <?php endif; ?>
                <?php foreach ($datos['tributos'] as $f): ?>
                    <tr>
                        <td><?php echo h($f['nombre']); ?></td>
                        <td class="text-right"><?php echo (int) $f['cantidad']; ?></td>
                        <td class="text-right"><?php echo h($fmt($f['base'])); ?></td>
                        <td class="text-right"><?php echo h($fmt($f['valor'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot><tr><th colspan="3" class="text-right">Total</th><th class="text-right"><?php echo h($fmt($datos['total_valor'])); ?></th></tr></tfoot>
        </table>
        <?php if (!empty($datos['ciudades'])): ?>
            <h4><b><?php echo __('ReteICA por ciudad'); ?></b></h4>
            <table class="table table-striped table-bordered table-condensed" style="max-width: 700px;">
                <thead><tr><th>Ciudad</th><th class="text-right">Cantidad</th><th class="text-right">Base</th><th class="text-right">Valor</th></tr></thead>
                <tbody>
                    <?php foreach ($datos['ciudades'] as $f): ?>
                        <tr>
                            <td><?php echo h($f['ciudad']); ?></td>
                            <td class="text-right"><?php echo (int) $f['cantidad']; ?></td>
                            <td class="text-right"><?php echo h($fmt($f['base'])); ?></td>
                            <td class="text-right"><?php echo h($fmt($f['valor'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    <?php else: ?>
        <p class="text-muted"><?php echo __('Compara lo calculado al facturar con lo que el cliente realmente retuvo. El período es la fecha de la factura.'); ?></p>
        <?php $clasesEstado = array('Igual' => 'label-success', 'Pendiente' => 'label-info', 'No retuvo' => 'label-danger', 'Diferente' => 'label-warning'); ?>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr><th>Fecha factura</th><th>Factura</th><th>NIT</th><th>Cliente</th><th>Retención</th>
                    <th class="text-right">Calculada</th><th class="text-right">Aplicada</th><th class="text-right">Diferencia</th><th>Estado</th></tr>
            </thead>
            <tbody>
                <?php if (empty($datos['filas'])): ?>
                    <tr><td colspan="9" class="text-center text-muted">No hay facturas con retenciones calculadas en el período.</td></tr>
                <?php endif; ?>
                <?php foreach ($datos['filas'] as $f): ?>
                    <tr>
                        <td><?php echo h($f['fecha']); ?></td>
                        <td><?php echo $this->Html->link($f['factura'], array('controller' => 'facturas', 'action' => 'view', $f['factura_id'])); ?></td>
                        <td><?php echo h($f['nit']); ?></td>
                        <td><?php echo h($f['cliente']); ?></td>
                        <td><?php echo h($f['retencion']); ?></td>
                        <td class="text-right"><?php echo h($fmt($f['calculado'])); ?></td>
                        <td class="text-right"><?php echo h($fmt($f['aplicado'])); ?></td>
                        <td class="text-right"><?php echo h($fmt($f['diferencia'])); ?></td>
                        <td><span class="label <?php echo h($clasesEstado[$f['estado']]); ?>"><?php echo h($f['estado']); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <?php if (!empty($datos['filas'])): ?>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Total</th>
                        <th class="text-right"><?php echo h($fmt($datos['total_calculado'])); ?></th>
                        <th class="text-right"><?php echo h($fmt($datos['total_aplicado'])); ?></th>
                        <th class="text-right"><?php echo h($fmt($datos['total_diferencia'])); ?></th>
                        <th></th>
                    </tr>
                </tfoot>
            <?php endif; ?>
        </table>
    <?php endif; ?>
</div>
