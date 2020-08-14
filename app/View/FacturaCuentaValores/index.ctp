<?php $this->layout = 'inicio';?>
<?php echo ($this->Html->script('bandeja/gestionBandejas.js')); ?>
<div class="facturas-cuentas-valores index">



    <?php echo $this->Form->create('FacturaCuentaValore', array('action' => 'search', 'method' => 'post', 'class' => 'form-inline')); ?>
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
    <?php echo $this->Form->create('Reporte', array('controller' => 'reportes', 'action' => 'descargarFacturaCuentaValores')); ?>
    <fieldset>
        <?php echo $this->Form->input('codigoDian', array('type' => 'hidden', 'name' => 'codigoDian', 'value' => $codigoDian)) ?>
        <?php echo $this->Form->input('numeroFactura', array('type' => 'hidden', 'name' => 'numeroFactura', 'value' => $numeroFactura)) ?>
        <?php echo $this->Form->input('fechaInicio', array('type' => 'hidden', 'name' => 'fechaInicio', 'value' => $fechaInicio)) ?>
        <?php echo $this->Form->input('fechaFin', array('type' => 'hidden', 'name' => 'fechaFin', 'value' => $fechaFin)) ?>
        <?php echo $this->Form->input('tipocuentas', array('type' => 'hidden', 'name' => 'tipocuentas', 'value' => $tipocuentas)) ?>
        <?php echo $this->Form->input('tipopagos', array('type' => 'hidden', 'name' => 'tipopagos', 'value' => $tipopagos)) ?>



        <?php echo $this->Form->submit('Descargar', array('class' => 'btn btn-primary')); ?>
    </fieldset>
    </form><br><br>
    <!-- Fin zona descargue reporte excel -->



	<legend><h2><b><?php echo __('Métodos de Pago por Factura'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                                <th><?php echo $this->Paginator->sort('factura_id'); ?></th>
                                <th><?php echo $this->Paginator->sort('created', 'Fecha'); ?></th>
                                <th><?php echo $this->Paginator->sort('cuenta_id'); ?></th>
                                <th><?php echo $this->Paginator->sort('tipopago_id'); ?></th>
                                <th><?php echo $this->Paginator->sort('valor'); ?></th>
                </tr>
                <?php foreach ($pagosFacturas as $pagofact): ?>
                    <?php if (!empty($pagofact['F']['id'])) {?>
                        <tr>
                            <td><?php echo !empty($pagofact['F']['consecutivodian']) ? h($pagofact['F']['consecutivodian']) : $pagofact['F']['codigo']; ?>&nbsp;</td>
                            <td><?php echo h($pagofact['F']['created']); ?>&nbsp;</td>
                            <td><?php echo h($pagofact['C']['descripcion']); ?>&nbsp;</td>
                            <td><?php echo h($pagofact['T']['descripcion']); ?>&nbsp;</td>
                            <td><?php echo h("$" . number_format($pagofact['FacturaCuentaValore']['valor'], 2)); ?>&nbsp;</td>
                        </tr>
                    <?php }?>
                <?php endforeach;?>
                </table>
            </div>
        </div>
        <?php
echo $this->Paginator->counter(array(
    'format' => __('Página {:page} de {:pages}, mostrando {:current} registro de {:count} en total, iniciando en registro {:start}, finalizando en {:end}'),
));
?>
        </p>
	<div class="pagin">
	<?php echo $this->Paginator->prev('< ' . __('Anterior '), array(), null, array('class' => 'prev disabled')); ?>
	<?php echo $this->Paginator->numbers(array('separator' => ' || ')); ?>
	<?php echo $this->Paginator->next(__(' Siguiente') . ' >', array(), null, array('class' => 'next disabled')); ?>
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
