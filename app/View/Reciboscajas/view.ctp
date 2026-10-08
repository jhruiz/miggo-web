<?php $this->layout = 'inicio'; ?>
<?php echo $this->Html->script('reciboscajas/imprimirRecibo'); ?>

<?php
    $estado     = $recibo['Reciboscaja']['estado'];
    $valor      = floatval($recibo['Reciboscaja']['valor']);
    $saldo      = floatval($recibo['Reciboscaja']['saldo']);
    $aplicado   = $valor - $saldo;
    $pctAplicado = $valor > 0 ? round(($aplicado / $valor) * 100) : 0;

    $badgeClass = 'badge-success';
    if ($estado === 'aplicado') $badgeClass = 'badge-info';
    if ($estado === 'anulado')  $badgeClass = 'badge-danger';
?>

<div class="reciboscajas view container-fluid" style="padding: 20px;">

    <!-- ── ENCABEZADO ── -->
    <div class="row mb-3 align-items-center">
        <div class="col-md-8">
            <h3 style="color:#2A3F54; margin:0;">
                <i class="fa fa-file-text-o"></i>
                Recibo de Caja &nbsp;
                <b><?php echo h($recibo['Reciboscaja']['consecutivo']); ?></b>
                &nbsp;
                <span class="badge <?php echo $badgeClass; ?>" style="font-size:14px;">
                    <?php echo ucfirst(h($estado)); ?>
                </span>
            </h3>
        </div>
        <div class="col-md-4 text-right">
            <?php echo $this->Html->link(
                '<i class="fa fa-arrow-left"></i> Volver al listado',
                array('action' => 'index'),
                array('class' => 'btn btn-default', 'escape' => false)
            ); ?>
            &nbsp;
            <?php if ($estado !== 'anulado'): ?>
            <button type="button" class="btn btn-danger" id="btnAnular"
                    data-id="<?php echo $recibo['Reciboscaja']['id']; ?>"
                    data-consecutivo="<?php echo h($recibo['Reciboscaja']['consecutivo']); ?>">
                <i class="fa fa-ban"></i> Anular
            </button>
            <?php endif; ?>
            &nbsp;
            <button type="button" class="btn btn-default" onclick="imprimirRecibo(<?php echo $recibo['Reciboscaja']['id']; ?>)">
                <i class="fa fa-print"></i> Imprimir
            </button>
        </div>
    </div>

    <div class="row">

        <!-- ── COLUMNA IZQUIERDA: datos del recibo ── -->
        <div class="col-md-5">

            <!-- Datos principales -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="card-title mb-0"><b><i class="fa fa-info-circle"></i> Información del Recibo</b></h4>
                </div>
                <div class="card-body" style="padding:0;">
                    <table class="table table-bordered mb-0" style="font-size:14px;">
                        <tbody>
                            <tr>
                                <th style="width:40%; background:#f8f9fa;">Consecutivo</th>
                                <td><b><?php echo h($recibo['Reciboscaja']['consecutivo']); ?></b></td>
                            </tr>
                            <tr>
                                <th style="background:#f8f9fa;">Fecha</th>
                                <td><?php echo date('d/m/Y H:i', strtotime($recibo['Reciboscaja']['created'])); ?></td>
                            </tr>
                            <tr>
                                <th style="background:#f8f9fa;">Registrado por</th>
                                <td><?php echo h($recibo['U']['nombre']); ?></td>
                            </tr>
                            <tr>
                                <th style="background:#f8f9fa;">Tipo de Pago</th>
                                <td><?php echo h($recibo['TP']['descripcion']); ?></td>
                            </tr>
                            <tr>
                                <th style="background:#f8f9fa;">Cuenta</th>
                                <td><?php echo h($recibo['CU']['descripcion']); ?></td>
                            </tr>
                            <?php if (!empty($recibo['Reciboscaja']['concepto'])): ?>
                            <tr>
                                <th style="background:#f8f9fa;">Concepto</th>
                                <td><?php echo h($recibo['Reciboscaja']['concepto']); ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Datos del cliente -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="card-title mb-0"><b><i class="fa fa-user"></i> Cliente</b></h4>
                </div>
                <div class="card-body" style="padding:0;">
                    <table class="table table-bordered mb-0" style="font-size:14px;">
                        <tbody>
                            <tr>
                                <th style="width:40%; background:#f8f9fa;">Nombre</th>
                                <td><b><?php echo h($recibo['CL']['nombre']); ?></b></td>
                            </tr>
                            <tr>
                                <th style="background:#f8f9fa;">NIT / CC</th>
                                <td><?php echo h($recibo['CL']['nit']); ?></td>
                            </tr>
                            <?php if (!empty($recibo['CL']['telefono'])): ?>
                            <tr>
                                <th style="background:#f8f9fa;">Teléfono</th>
                                <td><?php echo h($recibo['CL']['telefono']); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if (!empty($recibo['CL']['email'])): ?>
                            <tr>
                                <th style="background:#f8f9fa;">Email</th>
                                <td><?php echo h($recibo['CL']['email']); ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Resumen financiero -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="card-title mb-0"><b><i class="fa fa-bar-chart"></i> Resumen Financiero</b></h4>
                </div>
                <div class="card-body">
                    <!-- Barra de progreso -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between" style="font-size:12px; color:#7f8c8d; margin-bottom:4px;">
                            <span>Aplicado</span>
                            <span><?php echo $pctAplicado; ?>%</span>
                        </div>
                        <div class="progress" style="height:12px; border-radius:6px;">
                            <div class="progress-bar <?php echo $estado === 'anulado' ? 'bg-danger' : 'bg-success'; ?>"
                                 role="progressbar"
                                 style="width:<?php echo $pctAplicado; ?>%;">
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered mb-0" style="font-size:15px;">
                        <tbody>
                            <tr>
                                <th style="background:#f8f9fa;">Valor total</th>
                                <td class="text-right"><b>$<?php echo number_format($valor, 2); ?></b></td>
                            </tr>
                            <tr>
                                <th style="background:#f8f9fa;">Aplicado</th>
                                <td class="text-right" style="color:#e67e22;">
                                    $<?php echo number_format($aplicado, 2); ?>
                                </td>
                            </tr>
                            <tr style="<?php echo $estado === 'anulado' ? '' : 'background:#eafaf1;'; ?>">
                                <th style="background:<?php echo $estado === 'anulado' ? '#fdecea' : '#eafaf1'; ?>;">
                                    Saldo disponible
                                </th>
                                <td class="text-right"
                                    style="color:<?php echo $estado === 'anulado' ? '#e74c3c' : '#27ae60'; ?>; font-size:17px;">
                                    <b>$<?php echo number_format($saldo, 2); ?></b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div><!-- /col izquierda -->

        <!-- ── COLUMNA DERECHA: abonos aplicados ── -->
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <b><i class="fa fa-link"></i> Abonos Aplicados desde este Recibo</b>
                    </h4>
                    <span class="badge badge-secondary" style="font-size:13px; padding:5px 10px;">
                        <?php echo count($abonosAplicados); ?> abono(s)
                    </span>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($abonosAplicados)): ?>
                    <div class="text-center text-muted" style="padding:40px 20px;">
                        <i class="fa fa-inbox fa-3x" style="opacity:0.3;"></i>
                        <p class="mt-3">
                            <?php if ($estado === 'anulado'): ?>
                                Este recibo fue anulado. No tiene abonos aplicados.
                            <?php else: ?>
                                Este recibo aún no ha sido aplicado a ninguna factura o prefactura.<br>
                                <small>Puede aplicarlo desde el módulo de Facturas o Prefacturas.</small>
                            <?php endif; ?>
                        </p>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover mb-0" style="font-size:13px;">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Documento</th>
                                    <th>Cliente</th>
                                    <th class="text-right">Valor Aplicado</th>
                                    <th>Registrado por</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $totalAplicadoTabla = 0; ?>
                                <?php foreach ($abonosAplicados as $abono): ?>
                                <?php $totalAplicadoTabla += floatval($abono['Abonofactura']['valor']); ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($abono['Abonofactura']['created'])); ?></td>
                                    <td>
                                        <?php if (!empty($abono['Abonofactura']['factura_id'])): ?>
                                            <!-- Abono aplicado a factura -->
                                            <span class="badge badge-primary">Factura</span>
                                            <?php if (!empty($abono['F']['consecutivodian'])): ?>
                                                <b><?php echo h($abono['F']['consecutivodian']); ?></b>
                                            <?php elseif (!empty($abono['F']['codigo'])): ?>
                                                <b>#<?php echo h($abono['F']['codigo']); ?></b>
                                            <?php else: ?>
                                                <span class="text-muted">ID <?php echo h($abono['Abonofactura']['factura_id']); ?></span>
                                            <?php endif; ?>
                                        <?php elseif (!empty($abono['Abonofactura']['prefactura_id'])): ?>
                                            <!-- Abono aplicado a prefactura -->
                                            <span class="badge badge-warning">Prefactura</span>
                                            <?php if (!empty($abono['PF']['codigo'])): ?>
                                                <b>#<?php echo h($abono['PF']['codigo']); ?></b>
                                            <?php else: ?>
                                                <span class="text-muted">ID <?php echo h($abono['Abonofactura']['prefactura_id']); ?></span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo h($abono['CL']['nombre']); ?><br>
                                        <small class="text-muted"><?php echo h($abono['CL']['nit']); ?></small>
                                    </td>
                                    <td class="text-right">
                                        <b>$<?php echo number_format($abono['Abonofactura']['valor'], 2); ?></b>
                                    </td>
                                    <td>
                                        <small><?php echo h($abono['U']['nombre']); ?></small>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr style="background:#f8f9fa;">
                                    <th colspan="3" class="text-right">Total Aplicado:</th>
                                    <th class="text-right" style="color:#e67e22;">
                                        $<?php echo number_format($totalAplicadoTabla, 2); ?>
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div><!-- /card abonos -->

            <?php if ($estado === 'activo' && $saldo > 0): ?>
            <!-- Nota informativa cuando tiene saldo disponible -->
            <div class="alert alert-info mt-3" style="border-left:4px solid #3498db;">
                <i class="fa fa-info-circle"></i>
                <b>Saldo disponible: $<?php echo number_format($saldo, 2); ?></b><br>
                <small>
                    Este recibo tiene saldo pendiente de aplicar. Para usarlo como abono en una
                    factura o prefactura, búsquelo desde el módulo correspondiente usando el
                    consecutivo <b><?php echo h($recibo['Reciboscaja']['consecutivo']); ?></b>.
                </small>
            </div>
            <?php endif; ?>

        </div><!-- /col derecha -->

    </div><!-- /row -->
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
                <p>¿Está seguro que desea anular el recibo
                   <b><?php echo h($recibo['Reciboscaja']['consecutivo']); ?></b>?</p>
                <p class="text-muted small">
                    Solo es posible si no tiene abonos aplicados.
                    El valor de <b>$<?php echo number_format($valor, 2); ?></b>
                    será revertido de la cuenta <b><?php echo h($recibo['CU']['descripcion']); ?></b>.
                </p>
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
<input type="hidden" id="reciboId" value="<?php echo $recibo['Reciboscaja']['id']; ?>">

<script>
$(document).ready(function() {

    // Abrir modal de anulación
    $('#btnAnular').on('click', function() {
        $('#modalAnular').modal('show');
    });

    // Confirmar anulación
    $('#btnConfirmarAnular').on('click', function() {
        var baseUrl  = $('#baseUrl').val();
        var reciboId = <?php echo $recibo['Reciboscaja']['id']; ?>;

        $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Anulando...');

        $.ajax({
            type    : 'POST',
            url     : baseUrl + '/reciboscajas/ajaxAnularRecibo',
            data    : { reciboId: reciboId },
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
                    $('#btnConfirmarAnular').prop('disabled', false).html('<i class="fa fa-ban"></i> Confirmar Anulación');
                }
            }
        });
    });

});
</script>
