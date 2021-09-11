<?php $this->layout = 'inicio';?>
<?php echo ($this->Html->script('bandeja/gestionBandejas')); ?>
<?php echo ($this->Html->script('facturas/notacredito.js')); ?>

<div class="container body">
<div class="main_container">
<br>
<div class="facturas index">
<br>
     <div class="x_panel">

        <div class="x_title">
            <h2><b><?php echo __('Buscar Facturas'); ?></b></h2>
        </div>
        <?php echo $this->Form->create('Facturas', array('action' => 'search', 'method' => 'post', 'class' => 'form-inline')); ?>

            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '33', 'id' => 'menuvert')) ?>

            <div class="row" style="margin-bottom: 20px">

                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="codigo">Código</label><br>
                            <input name="codigo" id="codigo" autocomplete="off" class="form-control" placeholder="Código de la Factura" type="text">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="consecutivo">Consecutivo</label><br>
                            <input name="consecutivo" id="consecutivo" autocomplete="off" class="form-control" placeholder="Consecutivo DIAN" type="text">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="FacturasVendedor">Vendedor</label>
                            <?php echo $this->Form->input('vendedor', array('label' => '', 'name' => 'vendedor', 'empty' => 'Seleccione uno', 'type' => 'select', 'options' => $usuario, 'class' => 'form-control')); ?>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row" style="margin-bottom: 20px">

                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fechafactura">Fecha Inicio</label><br>
                            <input name="fechafactura" id="fechafactura" autocomplete="off" class="date form-control" placeholder="Fecha de Expedición" type="text">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fechafactura_fin">Fecha Fin</label><br>
                            <input name="fechafactura_fin" id="fechafactura_fin" autocomplete="off" class="date form-control" placeholder="Fecha de Expedición" type="text">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cliente">Cliente</label><br>
                            <input name="cliente" id="cliente" autocomplete="off" class="form-control" placeholder="Nombre Cliente" type="text">
                        </div>
                    </div>

                </div>
            </div>

            <div class="row" style="margin-bottom: 20px;">

                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="FacturasTipopago">Pago</label>
                            <?php echo $this->Form->input('tipopago', array('label' => '', 'name' => 'tipopago', 'empty' => 'Seleccione uno', 'type' => 'select', 'options' => $tipoPago, 'class' => 'form-control')); ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="vehiculo">Vehículo</label><br>
                            <input name="vehiculo" id="vehiculo" autocomplete="off" class="form-control" placeholder="Placa/Número Motor del vehículo" type="text">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="FacturasFactura">Tipo</label>
                            <?php echo $this->Form->input('factura', array('label' => '', 'name' => 'factura', 'empty' => 'Seleccione uno', 'type' => 'select', 'options' => array("1" => "F", "0" => "R"), 'class' => 'form-control')); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-3">
                <div class="form-group ">
                <?php echo $this->Form->submit('Buscar', array('class' => 'btn btn-primary')); ?>
                </div>
            </div>
            <div class="col-md-9">
                &nbsp;
            </div>
        </div>

        </form>
         </div><!-- class="x_panel" -->

      <div class="x_panel">
        <div class="x_title">
                    <h2><b><?php echo __('Facturas'); ?></b></h2>
                    <!--Enlaces de acción -->
                    <div class="actions">
                        <button type="button" class="btn btn-primary">
                        <?php echo $this->Html->link(__('Facturar'), array('action' => 'add'), ["style" => "color:white;"]); ?>
                        </button>
                    </div>
                    <ul class="nav navbar-right panel_toolbox">

                  </li>
                  <li class="dropdown">

                  </li>

                  </li>
                </ul>
             </div>
        <div class="table-responsive">
            <div class="container">
            <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                <tr>
                    <th><?php echo $this->Paginator->sort('codigo'); ?></th>
                    <th><?php echo $this->Paginator->sort('consecutivodian', 'Consecutivo'); ?></th>
                    <th><?php echo $this->Paginator->sort('cliente_id'); ?></th>
                    <th><?php echo $this->Paginator->sort('vehiculo_id'); ?></th>
                    <th><?php echo $this->Paginator->sort('usuario_id', 'Vendedor'); ?></th>
                    <th><?php echo $this->Paginator->sort('created', 'Fecha Factura'); ?></th>
                    <th><?php echo $this->Paginator->sort('factura', 'Tipo'); ?></th>
                    <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($facturas as $factura): ?>
                <tr>
                        <td><?php echo h($factura['Factura']['codigo']); ?>&nbsp;</td>
                        <td><?php echo h($factura['Factura']['consecutivodian']); ?>&nbsp;</td>
                        <td><?php echo h(!empty($factura['C']['nombre']) ? $factura['C']['nombre'] : "Anonimo"); ?>&nbsp;</td>
                        <td><?php echo h($factura['V']['placa']); ?>&nbsp;</td>
                        <td><?php echo h($factura['U']['nombre']); ?>&nbsp;</td>
                        <td><?php echo h($factura['Factura']['created']); ?>&nbsp;</td>
                        <td><?php echo h($factura['Factura']['factura'] ? "F" : "R"); ?>&nbsp;</td>
                        <td class="actions">
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Factura', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $factura['Factura']['id']))); ?>
                            <?php echo $this->Html->image('png/list-3.png', array('title' => 'Ver Detalle Factura', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'detalleventa', $factura['Factura']['id']))); ?>
                            <?php echo $this->Html->image('png/list-2.png', array('title' => 'Nota crédito', 'alt' => __('Brownies'), 'width' => '20px', 'onclick' => "generarNotaCredito(" . $factura['Factura']['id'] . ")", 'class' => 'nota_credito'));?>
                        </td>
                </tr>
    <?php endforeach;?>
            </table>
            </div>
        </div>
	<p>

	<?php
echo $this->Paginator->counter(array(
    'format' => __('Página {:page} de {:pages}, mostrando {:current} registro de {:count} en total, iniciando en registro {:start}, finalizando en {:end}'),
));
?>	</p>
	<div class="paging">
	<?php echo $this->Paginator->prev('< ' . __('Anterior '), array(), null, array('class' => 'prev disabled')); ?>
	<?php echo $this->Paginator->numbers(array('separator' => ' || ')); ?>
	<?php echo $this->Paginator->next(__(' Siguiente') . ' >', array(), null, array('class' => 'next disabled')); ?>
	</div>
</div><br><br>
<?php echo $this->Form->create('Reporte', array('controller' => 'reportes', 'action' => 'descargarFacturas')); ?>
    <fieldset>
        <?php echo $this->Form->input('rpcodigo', array('type' => 'hidden', 'name' => 'rpcodigo', 'value' => $rpcodigo)) ?>
        <?php echo $this->Form->input('rpconsecutivo', array('type' => 'hidden', 'name' => 'rpconsecutivo', 'value' => $rpconsecutivo)) ?>
        <?php echo $this->Form->input('rpvendedor', array('type' => 'hidden', 'name' => 'rpvendedor', 'value' => $rpvendedor)) ?>
        <?php echo $this->Form->input('rpfecha', array('type' => 'hidden', 'name' => 'rpfecha', 'value' => $rpfecha)) ?>
        <?php echo $this->Form->input('rpfechaFin', array('type' => 'hidden', 'name' => 'rpfechaFin', 'value' => $rpfechaFin)) ?>
        <?php echo $this->Form->input('rpvencimiento', array('type' => 'hidden', 'name' => 'rpvencimiento', 'value' => $rpvencimiento)) ?>
        <?php echo $this->Form->input('rptipopago', array('type' => 'hidden', 'name' => 'rptipopago', 'value' => $rptipopago)) ?>
        <?php echo $this->Form->input('esfactura', array('type' => 'hidden', 'name' => 'esfactura', 'value' => $esFactura)) ?>
        <?php echo $this->Form->submit('Descargar', array('class' => 'btn btn-primary')); ?>
    </fieldset>
</form><br><br>

  </div><!-- class="x_panel"-->

</div> <!-- class="container body -->
</div> <!-- class="main_container" -->
<div id="div_notacredito"></div>
