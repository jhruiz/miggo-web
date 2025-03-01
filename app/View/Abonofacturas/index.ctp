<?php $this->layout = 'inicio';?>
<?php echo ($this->Html->script('bandeja/gestionBandejas.js')); ?>
<div class="abonofacturas index">

<?php $totalValor = 0; ?>

    <?php echo $this->Form->create('Abonofactura', array('action' => 'search', 'method' => 'post', 'class' => 'form-inline')); ?>
    <legend><h2><b><?php echo __('Buscar de pagos por factura'); ?></b></h2></legend>
    <div class="row">
        <div class="col-md-3"> 
            <div class="form-group">
                <label>Código Dian</label><br>
                <input name="data[codigoDian]" id="codigoDian" autocomplete="off" class="form-control" placeholder="Código Dian" type="text">
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label>Código Factura</label><br>
                <input name="data[numeroFactura]" id="numeroFactura" autocomplete="off" class="form-control" placeholder="Código de factura" type="text">
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label>Prefactura</label><br>
                <input name="data[numeroPrefactura]" id="numeroPrefactura" autocomplete="off" class="form-control" placeholder="Código de prefactura" type="text">
            </div>
        </div>

        <div class="col-md-3">
            <label>Cuentas</label>
            <?php
                echo $this->Form->input("tipocuentas",
                    array(
                        'name' => "tipocuentas",
                        'label' => "",
                        'type' => 'select',
                        'options' => $tipoCuentas,
                        'empty' => 'Seleccione Uno',
                        'class' => 'form-control',
                    )
                );
            ?>
        </div>
    </div>

    <div class="row" style="margin-top:20px;">        

        <div class="col-md-3">
            <div class="form-group">
                <label>Fecha Inicial</label><br>
                <input name="data[fechaInicio]" id="fechaInicio" autocomplete="off" class="date form-control" placeholder="Fecha Inicio" type="text">
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label>Fecha Final</label><br>
                <input name="data[fechaFin]" id="fechaFin" autocomplete="off" class="date form-control" placeholder="Fecha Fin" type="text">
            </div>
        </div>

        <div class="col-md-3">
            <label>Tipos de Pago</label>
            <?php
            echo $this->Form->input("tipopagos",
                array(
                    'name' => "tipopagos",
                    'label' => "",
                    'type' => 'select',
                    'options' => $tipoPago,
                    'empty' => 'Seleccione Uno',
                    'class' => 'form-control',
                )
            );
            ?>
        </div>
    </div><br>
    <?php echo $this->Form->submit('Buscar', array('class' => 'btn btn-primary')); ?>
    </form><br><br>

    <!-- Inicio zona descargue reporte excel-->
    <?php echo $this->Form->create('Reporte', array('controller' => 'reportes', 'action' => 'descargarAbonosFacturasCuentas')); ?>
    <fieldset>
        <?php echo $this->Form->input('codigoDian', array('type' => 'hidden', 'name' => 'codigoDian', 'value' => $codigoDian)) ?>
        <?php echo $this->Form->input('numeroFactura', array('type' => 'hidden', 'name' => 'numeroFactura', 'value' => $numeroFactura)) ?>
        <?php echo $this->Form->input('numeroPrefactura', array('type' => 'hidden', 'name' => 'numeroPrefactura', 'value' => $numeroPrefactura)) ?>
        <?php echo $this->Form->input('fechaInicio', array('type' => 'hidden', 'name' => 'fechaInicio', 'value' => $fechaInicio)) ?>
        <?php echo $this->Form->input('fechaFin', array('type' => 'hidden', 'name' => 'fechaFin', 'value' => $fechaFin)) ?>
        <?php echo $this->Form->input('tipocuentas', array('type' => 'hidden', 'name' => 'tipocuentas', 'value' => $tipocuenta)) ?>
        <?php echo $this->Form->input('tipopagos', array('type' => 'hidden', 'name' => 'tipopagos', 'value' => $tipopagos)) ?>
        <?php echo $this->Form->submit('Descargar', array('class' => 'btn btn-primary')); ?>
    </fieldset>
    </form><br><br>
    <!-- Fin zona descargue reporte excel -->

	<legend><h2><b><?php echo __('Métodos de Pago por Abonos'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                                <th><?php echo ('# Prefactura'); ?></th>
                                <th><?php echo ('# Factura'); ?></th>
                                <th><?php echo ('# Consecutivo Dian'); ?></th>
                                <th><?php echo ('Usuario'); ?></th>
                                <th><?php echo ('Fecha'); ?></th>
                                <th><?php echo ('Tipo de Pago'); ?></th>
                                <th><?php echo ('Cuenta'); ?></th>
                                <th><?php echo ('Cliente'); ?></th>
                                <th><?php echo ('Valor'); ?></th>
                </tr>
                <?php foreach ($abonos as $ab): ?>
                        <tr>
                            <td><?php echo h($ab['Abonofactura']['prefactura_id']); ?>&nbsp;</td>
                            <td><?php echo h($ab['F']['codigo']); ?>&nbsp;</td>
                            <td><?php echo h($ab['F']['consecutivodian']); ?>&nbsp;</td>
                            <td><?php echo h($ab['U']['nombre']); ?>&nbsp;</td>
                            <td><?php echo h($ab['Abonofactura']['created']); ?>&nbsp;</td>
                            <td><?php echo h($ab['TP']['descripcion']); ?>&nbsp;</td>
                            <td><?php echo h($ab['CU']['descripcion']); ?>&nbsp;</td>
                            <td><?php echo h($ab['C']['nombre']); ?>&nbsp;</td>
                            <td><?php echo h("$" . number_format($ab['Abonofactura']['valor'], 2)); ?>&nbsp;</td>
                        </tr>
                <?php $totalValor += $ab['Abonofactura']['valor'];?>
                <?php endforeach;?>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                &nbsp;
            </div>
            <div class="col-md-2">
                <dl>
                    <dt class="text-left text-success"><?php echo h("Valor Total: "); ?></dt>
                </dl>
            </div>
            <div class="col-md-2">
                <dl>
                    <dt class="text-right text-success"><?php echo h("$" . number_format($totalValor, 2)) ?></dt>
                </dl>
            </div>
        </div>
</div>
