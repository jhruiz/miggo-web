<?php 
$this->layout = false;
echo $this->Html->css('abonos/obtenerabonos.css', array('rel' => 'stylesheet', 'media' => 'all'));
echo $this->Html->script('abonos/gestionabonos.js');
?>

<div class="table-responsive">
    <div class="container">
        <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
            <thead>
                <tr>
                    <th><?php echo __('Cliente'); ?></th>
                    <th><?php echo __('Identificación'); ?></th>
                    <th><?php echo __('Usuario'); ?></th>
                    <th><?php echo __('Cuenta'); ?></th>
                    <th><?php echo __('Fecha'); ?></th>
                    <th><?php echo __('Origen'); ?></th>
                    <th><?php echo __('Valor'); ?></th>
                    <th><?php echo __('Acciones'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php $total = 0; ?>
            <?php foreach ($abonos as $abono): ?>
            <?php
                $reciboId          = !empty($abono['Abonofactura']['recibocaja_id']) ? $abono['Abonofactura']['recibocaja_id'] : null;
                $reciboConsecutivo = !empty($abono['RC']['consecutivo']) ? $abono['RC']['consecutivo'] : null;
                $esDeRecibo        = !empty($reciboId);
            ?>
                <tr id="fila-<?php echo h($abono['Abonofactura']['id']); ?>">
                    <td><?php echo h($abono['C']['nombre']); ?></td>
                    <td><?php echo h($abono['C']['nit']); ?></td>
                    <td><?php echo h($abono['U']['nombre']); ?></td>
                    <td><?php echo h($abono['CU']['descripcion']); ?></td>
                    <td><?php echo h($abono['Abonofactura']['created']); ?></td>

                    <!-- Columna Origen: indica si viene de un recibo de caja o es un abono manual -->
                    <td>
                        <?php if ($esDeRecibo): ?>
                            <span class="badge badge-info" title="Originado desde recibo de caja <?php echo h($reciboConsecutivo); ?>">
                                <i class="fa fa-file-text-o"></i>
                                <?php echo h($reciboConsecutivo); ?>
                            </span>
                        <?php else: ?>
                            <span class="badge badge-default" style="background:#95a5a6;">
                                <i class="fa fa-pencil"></i> Manual
                            </span>
                        <?php endif; ?>
                    </td>

                    <td class="valor"><?php echo h('$' . number_format($abono['Abonofactura']['valor'], 2)); ?></td>

                    <td class="actions" style="white-space:nowrap;">
                        <?php if (!$esDeRecibo): ?>
                        <!-- Editar: solo disponible para abonos manuales -->
                        <i class="fa fa-pencil fa-lg text-primary"
                            id="<?php echo h($abono['Abonofactura']['id']); ?>"
                            data-valor="<?php echo h($abono['Abonofactura']['valor']); ?>"
                            data-cuenta="<?php echo h($abono['CU']['id']); ?>"
                            data-fecha="<?php echo h($abono['Abonofactura']['created']); ?>"
                            style="cursor:pointer; margin-right:6px;"
                            title="Editar abono"
                            onclick="setearEditarAbono(this);"></i>
                        <?php else: ?>
                        <!-- Abonos de recibo no se pueden editar (afectaría el saldo del recibo) -->
                        <i class="fa fa-lock fa-lg text-muted"
                            style="margin-right:6px;"
                            title="Abono originado desde recibo de caja. Para ajustar, gestione el recibo <?php echo h($reciboConsecutivo); ?>."></i>
                        <?php endif; ?>

                        <!-- Eliminar: disponible para todos, pero pasa recibocaja_id para devolver saldo -->
                        <i class="fa fa-trash-o fa-lg text-danger"
                            id="<?php echo h($abono['Abonofactura']['id']); ?>"
                            data-valor="<?php echo h($abono['Abonofactura']['valor']); ?>"
                            data-cuenta="<?php echo h($abono['CU']['id']); ?>"
                            data-prefactura="<?php echo h($prefacturaId); ?>"
                            data-factura=""
                            data-recibocaja="<?php echo h($reciboId ?: ''); ?>"
                            style="cursor:pointer;"
                            title="Eliminar abono"
                            onclick="eliminarAbono(this);"></i>
                    </td>
                </tr>
                <?php $total += $abono['Abonofactura']['valor']; ?>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5"></td>
                    <td><b>TOTAL</b></td>
                    <td id="totalesAbonos"><b><?php echo h('$' . number_format($total, 2)); ?></b></td>
                    <td>&nbsp;</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Formulario de edición de abono (solo manual) -->
<div class="container d-flex justify-content-center align-items-center"
     style="width:50%; margin:0 auto; padding:20px;"
     id="formEditarAbono">
    <h2 class="mt-4"><b>Datos del abono</b></h2>
    <form class="form">
        <div class="form-group mr-2">
            <label for="fechaAbono">Fecha del abono:</label>
            <input type="text" class="form-control form-control-sm" id="fechaAbono" disabled="disabled">
        </div>
        <div class="form-group mr-2">
            <label for="cuenta">Cuenta:</label>
            <select class="form-control form-control-sm" id="cuenta">
                <?php foreach ($cuentas as $key => $val): ?>
                    <option value="<?php echo h($key); ?>"><?php echo h($val); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group mr-2">
            <label for="valorAbono">Valor del abono:</label>
            <input type="text" class="form-control form-control-sm" id="valorAbono" placeholder="Valor del abono">
        </div>
        <input type="hidden" id="valorAbonoHidden">
        <input type="hidden" id="idAbono">
        <input type="hidden" id="idPrefactura" value="<?php echo h($prefacturaId); ?>">
        <input type="hidden" id="reciboCajaAbono" value="">
        <div>
            <button type="button" class="btn btn-primary btn-sm" id="btnActAbono">Actualizar</button>
            <button type="button" class="btn btn-primary btn-sm" id="btnHide">Limpiar</button>
        </div>
    </form>
</div>
