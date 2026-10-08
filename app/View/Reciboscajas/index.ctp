<?php $this->layout = 'inicio'; ?>

<?php echo ($this->Html->script('bandeja/gestionBandejas.js'));?>

<div class="reciboscajas index container-fluid" style="padding: 20px;">

    <!-- ── FILTROS ── -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h3 class="card-title mb-0"><b><i class="fa fa-search"></i> Buscar Recibos de Caja</b></h3>
        </div>
        <div class="card-body">
            <?php echo $this->Form->create('Reciboscaja', array('action' => 'search', 'method' => 'post', 'class' => 'form-horizontal')); ?>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Consecutivo</label>
                        <?php echo $this->Form->input('consecutivo', array(
                            'label'       => false,
                            'class'       => 'form-control',
                            'placeholder' => 'Ej: RC-0001',
                            'value'       => $filtConsecutivo,
                        )); ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Cliente</label>
                        <?php echo $this->Form->input('cliente', array(
                            'label'       => false,
                            'class'       => 'form-control',
                            'placeholder' => 'Nombre del cliente',
                            'value'       => $filtCliente,
                        )); ?>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Estado</label>
                        <?php echo $this->Form->input('estado', array(
                            'label'   => false,
                            'type'    => 'select',
                            'class'   => 'form-control',
                            'empty'   => 'Todos',
                            'options' => array(
                                'activo'   => 'Activo',
                                'aplicado' => 'Aplicado',
                                'anulado'  => 'Anulado',
                            ),
                            'value'   => $filtEstado,
                        )); ?>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Cuenta</label>
                        <?php echo $this->Form->input('cuenta', array(
                            'label'   => false,
                            'type'    => 'select',
                            'class'   => 'form-control',
                            'empty'   => 'Todas',
                            'options' => $cuentas,
                            'value'   => $filtCuenta,
                        )); ?>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Tipo de Pago</label>
                        <?php echo $this->Form->input('tipopago', array(
                            'label'   => false,
                            'type'    => 'select',
                            'class'   => 'form-control',
                            'empty'   => 'Todos',
                            'options' => $tipoPagos,
                            'value'   => $filtTipoPago,
                        )); ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Fecha Desde</label>
                        <input type="text" name="data[Reciboscaja][fechaIni]"
                               class="date form-control"
                               placeholder="AAAA-MM-DD"
                               value="<?php echo h($filtFechaIni); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Fecha Hasta</label>
                        <input type="text" name="data[Reciboscaja][fechaFin]"
                               class="date form-control"
                               placeholder="AAAA-MM-DD"
                               value="<?php echo h($filtFechaFin); ?>">
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-group mb-3 w-100">
                        <?php echo $this->Form->submit('Buscar', array('class' => 'btn btn-primary btn-block')); ?>
                    </div>
                </div>
            </div>

            <?php echo $this->Form->end(); ?>
        </div>
    </div>

    <!-- ── ACCIONES ── -->
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <?php echo $this->Html->link(
                '<i class="fa fa-plus"></i> Nuevo Recibo de Caja',
                array('action' => 'add'),
                array('class' => 'btn btn-success', 'escape' => false)
            ); ?>
        </div>
        <div class="col-md-6 text-right">
            <?php
                $totalValor = 0;
                $totalSaldo = 0;
                foreach ($recibos as $r) {
                    if ($r['Reciboscaja']['estado'] !== 'anulado') {
                        $totalValor += floatval($r['Reciboscaja']['valor']);
                        $totalSaldo += floatval($r['Reciboscaja']['saldo']);
                    }
                }
            ?>
            <span class="badge badge-secondary" style="font-size:13px; padding:6px 12px;">
                Total recibido: <b>$<?php echo number_format($totalValor, 2); ?></b>
            </span>
            &nbsp;
            <span class="badge badge-info" style="font-size:13px; padding:6px 12px;">
                Saldo disponible: <b>$<?php echo number_format($totalSaldo, 2); ?></b>
            </span>
        </div>
    </div>

    <!-- ── TABLA ── -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h3 class="card-title mb-0"><b>Listado de Recibos de Caja</b></h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Consecutivo</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Concepto</th>
                            <th class="text-right">Valor</th>
                            <th class="text-right">Saldo Disp.</th>
                            <th>Cuenta</th>
                            <th>Tipo Pago</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recibos)): ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted" style="padding:30px;">
                                <i class="fa fa-inbox fa-2x"></i><br>
                                No se encontraron recibos de caja para los filtros seleccionados.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($recibos as $recibo): ?>
                        <?php
                            $estado  = $recibo['Reciboscaja']['estado'];
                            $badgeClass = 'badge-success';
                            if ($estado === 'aplicado') $badgeClass = 'badge-info';
                            if ($estado === 'anulado')  $badgeClass = 'badge-danger';
                        ?>
                        <tr>
                            <td><b><?php echo h($recibo['Reciboscaja']['consecutivo']); ?></b></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($recibo['Reciboscaja']['created'])); ?></td>
                            <td>
                                <?php echo h($recibo['CL']['nombre']); ?><br>
                                <small class="text-muted"><?php echo h($recibo['CL']['nit']); ?></small>
                            </td>
                            <td>
                                <small><?php echo h(!empty($recibo['Reciboscaja']['concepto']) ? $recibo['Reciboscaja']['concepto'] : '—'); ?></small>
                            </td>
                            <td class="text-right">
                                <b>$<?php echo number_format($recibo['Reciboscaja']['valor'], 2); ?></b>
                            </td>
                            <td class="text-right">
                                <?php if ($estado === 'anulado'): ?>
                                    <span class="text-muted">—</span>
                                <?php else: ?>
                                    <b style="color:<?php echo floatval($recibo['Reciboscaja']['saldo']) > 0 ? '#27ae60' : '#7f8c8d'; ?>">
                                        $<?php echo number_format($recibo['Reciboscaja']['saldo'], 2); ?>
                                    </b>
                                <?php endif; ?>
                            </td>
                            <td><small><?php echo h($recibo['CU']['descripcion']); ?></small></td>
                            <td><small><?php echo h($recibo['TP']['descripcion']); ?></small></td>
                            <td>
                                <span class="badge <?php echo $badgeClass; ?>">
                                    <?php echo ucfirst(h($estado)); ?>
                                </span>
                            </td>
                            <td class="text-center" style="min-width:90px;">
                                <!-- Ver detalle -->
                                <?php echo $this->Html->link(
                                    '<i class="fa fa-eye" title="Ver detalle"></i>',
                                    array('action' => 'view', $recibo['Reciboscaja']['id']),
                                    array('escape' => false, 'style' => 'margin-right:6px; color:#2980b9;')
                                ); ?>
                                <!-- Anular (solo si está activo o aplicado) -->
                                <?php if ($estado !== 'anulado'): ?>
                                <a href="javascript:void(0);"
                                   onclick="anularRecibo(<?php echo $recibo['Reciboscaja']['id']; ?>, '<?php echo h($recibo['Reciboscaja']['consecutivo']); ?>')"
                                   style="color:#e74c3c;"
                                   title="Anular recibo">
                                    <i class="fa fa-ban"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ── MODAL confirmación anular ── -->
<div class="modal fade" id="modalAnular" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-ban text-danger"></i> Anular Recibo</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea anular el recibo <b id="lblConsecutivoAnular"></b>?</p>
                <p class="text-muted small">Esta acción solo es posible si el recibo no tiene abonos aplicados.
                   El valor será revertido de la cuenta correspondiente.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarAnular">
                    <i class="fa fa-ban"></i> Confirmar Anulación
                </button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="baseUrl" value="<?php echo $this->request->base; ?>">

<script>
var reciboIdAnular = null;

function anularRecibo(id, consecutivo) {
    reciboIdAnular = id;
    $('#lblConsecutivoAnular').text(consecutivo);
    $('#modalAnular').modal('show');
}

$('#btnConfirmarAnular').on('click', function() {
    if (!reciboIdAnular) return;

    var baseUrl = $('#baseUrl').val();

    $.ajax({
        type    : 'POST',
        url     : baseUrl + '/reciboscajas/ajaxAnularRecibo',
        data    : { reciboId: reciboIdAnular },
        success : function(data) {
            var resp = JSON.parse(data);
            $('#modalAnular').modal('hide');
            if (resp.resp) {
                bootbox.alert({
                    message  : '<i class="fa fa-check-circle text-success"></i> ' + resp.msg,
                    callback : function() { location.reload(); }
                });
            } else {
                bootbox.alert('<i class="fa fa-exclamation-circle text-danger"></i> ' + resp.msg);
            }
        }
    });
});
</script>
