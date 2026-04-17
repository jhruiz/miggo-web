<?php
$this->layout = 'inicio';
?>
<div class="cargueinventarios index">



    <div class="x_panel">

        <div class="x_title">
            <h2><b><?php echo __('Buscar productos en inventario'); ?></b></h2>
        </div>
        <?php echo $this->Form->create('Cargueinventario', array('action' => 'search', 'method' => 'post', 'class' => 'form-inline')); ?>

            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '11', 'id' => 'menuvert')) ?>

            <div class="row" style="margin-bottom: 20px">

                <div class="col-md-12">
                    <div class="col-md-3">
                        <div class="form-group ">
                            <label for="producto">Producto</label><br>
                            <?php echo $this->Form->input('producto', array('label' => false, 'name' => 'producto','id' => 'producto', 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Nombre de Producto')); ?>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group ">
                            <label for="codigo">Código</label><br>
                            <?php echo $this->Form->input('codigo', array('label' => false, 'name' => 'codigo','id' => 'codigo', 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Código de Producto')); ?>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group ">
                            <label for="deposito">Bodega</label><br>
                            <?php echo $this->Form->input('deposito', array('label' => '', 'name' => 'deposito', 'empty' => 'Seleccione una', 'type' => 'select', 'options' => $depositos, 'class' => 'form-control')); ?>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group ">
                            <label for="categoria">Categoría del Producto</label><br>
                            <?php echo $this->Form->input('categoria', array('label' => '', 'name' => 'categoria', 'empty' => 'Seleccione una', 'type' => 'select', 'options' => $categorias, 'class' => 'form-control')); ?>
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

    <legend><h2><b><?php echo __('Inventario'); ?></b></h2></legend>

    	<!--Enlaces de acción -->
		<div class="actions">
            <button type="button" class="btn btn-primary">
                <?php echo $this->Html->link(__('Nuevo Cargue Inventario'), array('action' => 'add'), ["style" => "color:white;"]); ?>
            </button>
        </div>

        <br>
        <div class="table-responsive">
            <div class="container">
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                                <th><?php echo h('Producto'); ?></th>
                                <th><?php echo h('Código'); ?></th>
                                <th><?php echo h('Bodega'); ?></th>
                                <th><?php echo h('Proveedor'); ?></th>
                                <th><?php echo h('Valor'); ?></th>
                                <th><?php echo h('Existencia Actual'); ?></th>
                                <th><?php echo h('Unidades a reponer'); ?></th>
                                <th><?php echo h('En Prefacturas'); ?></th>
                                <th><?php echo h('En Ordenes'); ?></th>
                                <th><?php echo h('Precio de Venta'); ?></th>
                                <th><?php echo h('Fecha de Cargue'); ?></th>
                                <th><?php echo h('Movimientos'); ?></th>
                </tr>
                <?php foreach ($cargueinventariosP as $cargueinventario): ?>
                <tr class="<?php echo $cargueinventario['Cargueinventario']['color']; ?>">
                        <td>
                                <?php echo $this->Html->link($cargueinventario['Producto']['descripcion'], array('controller' => 'productos', 'action' => 'view', $cargueinventario['Producto']['id'])); ?>
                        </td>
                        <td>
                                <?php echo ($cargueinventario['Producto']['codigo']); ?>
                        </td>
                        <td>
                                <?php echo $this->Html->link($cargueinventario['Deposito']['descripcion'], array('controller' => 'depositos', 'action' => 'view', $cargueinventario['Deposito']['id'])); ?>
                        </td>
                        <td><?php echo h($cargueinventario['Proveedore']['nombre']); ?>&nbsp;</td>
                        <td><?php echo h("$" . number_format($cargueinventario['Cargueinventario']['costoproducto'], 2)); ?>&nbsp;</td>
                        <td><?php echo h($cargueinventario['Producto']['inventario'] == '1' ? $cargueinventario['Cargueinventario']['existenciaactual'] : 'N/A'); ?>&nbsp;</td>
                        <td><?php echo h($cargueinventario['Producto']['inventario'] == '1' ? $cargueinventario['Cargueinventario']['unidadesReponer'] : 'N/A'); ?>&nbsp;</td>
                        <td><?php echo h($cargueinventario['Cargueinventario']['prefacturas']); ?>&nbsp;</td>
                        <td><?php echo h($cargueinventario['Cargueinventario']['ordeninsumos']); ?>&nbsp;</td>
                        <td><?php echo h("$" . number_format($cargueinventario['Cargueinventario']['precioventa'], 2)); ?>&nbsp;</td>
                        <td><?php echo h($cargueinventario['Cargueinventario']['created']); ?>&nbsp;</td>
                        <td class="actions">
                                    <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'viewmovements', 'controller' => 'detalledocumentos', $cargueinventario['Producto']['id']))); ?>
                        </td>

                    </tr>
                <?php endforeach;?>

                </table>
                <legend>&nbsp;</legend>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            &nbsp;
                        </div>
                        <div class="col-md-2">
                            <dl>
                                <dt class="text-left text-success"><?php echo h("Total Unidades: "); ?></dt>
                                <dt class="text-left text-success"><?php echo h("Costo Total: "); ?></dt>
                                <dt class="text-left text-info"><?php echo h("Cuenta por Pagar: "); ?></dt>
                            </dl>
                        </div>
                        <div class="col-md-2">
                            <dl>
                                <dt class="text-right text-success"><?php echo h(number_format($totalUnidades['0']['0']['stock'], 2)) ?></dt>
                                <dt class="text-right text-success"><?php echo h("$" . number_format($valorInventario, 2)) ?></dt>
                                <dt class="text-right text-info"><?php echo h("$" . number_format($totalDeuda, 2)) ?></dt>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

	<p>
	<?php
echo $this->Paginator->counter(array(
    'format' => __('Página {:page} de {:pages}, mostrando {:current} registro de {:count} en total, iniciando en registro {:start}, finalizando en {:end}'),
));
?>	</p>
	<div class="paging">
	<?php
echo $this->Paginator->prev('< ' . __('Anterior '), array(), null, array('class' => 'prev disabled'));
echo $this->Paginator->numbers(array('separator' => ' || '));
echo $this->Paginator->next(__(' Siguiente') . ' >', array(), null, array('class' => 'next disabled'));
?>
	</div>
</div><br>
<?php echo $this->Form->create('Reporte', array('controller' => 'reportes', 'action' => 'descargarInventario')); ?>
    <fieldset>
        <?php echo $this->Form->input('rpproducto', array('type' => 'hidden', 'name' => 'rpproducto', 'value' => $producto, 'id' => 'rpproducto_id')) ?>
        <?php echo $this->Form->input('rpdeposito', array('type' => 'hidden', 'name' => 'rpdeposito', 'value' => $deposito, 'id' => 'rpdeposito_id')) ?>
        <?php echo $this->Form->submit('Descargar', array('class' => 'btn btn-primary')); ?>
    </fieldset>
</form>
<br>
