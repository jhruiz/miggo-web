<?php
    echo ($this->Html->script('cargueinventario/cargueinventariosindex.js'));
    $this->layout='inicio'; 
?>
<div class="cargueinventarios index">

            <?php echo $this->Form->create('Cargueinventario',array('action'=>'search','method'=>'post', 'class' => 'form-inline'));?>
            <legend><h2><b><?php echo __('Buscar Inventario'); ?></b></h2></legend>      
            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '11', 'id' => 'menuvert'))?> 
            
            <div class="row">
                <?php echo $this->Form->input('empresa', array('type' => 'hidden', 'value' => $empresaId, 'id' => 'empresa_id'))?>
                <?php echo $this->Form->input('producto', array('type' => 'hidden', 'name' => 'producto', 'value' => '', 'id' => 'producto_id'))?>
                <div class="col-md-3">
                    <div class="form-group ">  
                        <label>Producto</label><br>                          
                        <?php echo $this->Form->input('buscarproducto', array('label' => false, 'id' => 'buscarproducto', 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Selecci車n de Producto', 'onkeyup' => 'fnObtenerDatosProducto(event);')); ?>
                        <div id="datosProducto" style="position:absolute; z-index:1;"></div> <br>   
                    </div>             
                </div>

                <div class="col-md-3">
                    <div class="form-group ">  
                        <label>Depósito</label><br>                          
                        <?php echo $this->Form->input('deposito', array('label' => '', 'name' => 'deposito', 'empty' => 'Seleccione uno', 'type' => 'select', 'options' => $depositos, 'class' => 'form-control'));?>
                    </div>             
                </div>       
                
                <div class="col-md-6">
                    &nbsp;
                </div>                    
            </div> 
            
        <div class="row">
            <div class="col-md-3">
                <div class="form-group ">  
                <?php echo $this->Form->submit('Buscar',array('class'=>'btn btn-primary'));?>                
                </div>             
            </div>
            <div class="col-md-9">
                &nbsp;
            </div>
        </div>            

        </form><br><br>              
        
    <legend><h2><b><?php echo __('Inventario'); ?></b></h2></legend>
	
	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('Nuevo Cargue Inventario'), array('action' => 'add')); ?></li> 
		</ul>
	</div><br><br>   
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                                <th><?php echo h('Producto'); ?></th>
                                <th><?php echo h('Dep車sito'); ?></th>
                                <th><?php echo h('Valor'); ?></th>
                                <th><?php echo h('Existencia Actual'); ?></th>
                                <th><?php echo h('Precio de Venta'); ?></th>
                                <th><?php echo h('Fecha de Cargue'); ?></th>
                                <th><?php echo h('Movimientos'); ?></th>                                
                </tr>
                <?php foreach ($cargueinventariosP as $cargueinventario): ?>
                <tr class="<?php echo $cargueinventario['Cargueinventario']['color'];?>">                    
                        <td>
                                <?php echo $this->Html->link($cargueinventario['Producto']['descripcion'], array('controller' => 'productos', 'action' => 'view', $cargueinventario['Producto']['id'])); ?>
                        </td>
                        <td>
                                <?php echo $this->Html->link($cargueinventario['Deposito']['descripcion'], array('controller' => 'depositos', 'action' => 'view', $cargueinventario['Deposito']['id'])); ?>
                        </td>
                        <td><?php echo h("$" . number_format($cargueinventario['Cargueinventario']['costoproducto'],2)); ?>&nbsp;</td>
                        <td><?php echo h($cargueinventario['Cargueinventario']['existenciaactual']); ?>&nbsp;</td>
                        <td><?php echo h("$" . number_format($cargueinventario['Cargueinventario']['precioventa'],2)); ?>&nbsp;</td>
                        <td><?php echo h($cargueinventario['Cargueinventario']['created']); ?>&nbsp;</td>
		<td class="actions">
                    <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'viewmovements', 'controller' => 'detalledocumentos', $cargueinventario['Producto']['id']))); ?>
		</td>                        
                        
                    </tr>
                <?php endforeach; ?>

                </table>
                <legend>&nbsp;</legend>
                
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            &nbsp;
                        </div>              
                        <div class="col-md-2">
                            <dl>                    
                                <dt class="text-left text-success"><?php echo h("Total Unidades: ");?></dt>
                                <dt class="text-left text-success"><?php echo h("Costo Total: ");?></dt>
                                <dt class="text-left text-info"><?php echo h("Cuenta por Pagar: ");?></dt>
                            </dl>
                        </div>         
                        <div class="col-md-2">                
                            <dl>
                                <dt class="text-right text-success"><?php echo h(number_format($totalUnidades['0']['0']['stock'],2))?></dt>
                                <dt class="text-right text-success"><?php echo h("$" . number_format($valorInventario,2))?></dt>
                                <dt class="text-right text-info"><?php echo h("$" . number_format($totalDeuda,2))?></dt>
                            </dl>
                        </div>        
                    </div>
                </div>                                
            </div>
        </div> 
        
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Página {:page} de {:pages}, mostrando {:current} registro de {:count} en total, iniciando en registro {:start}, finalizando en {:end}')
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
<?php echo $this->Form->create('Reporte',array( 'controller' => 'reportes','action'=>'descargarInventario')); ?>
    <fieldset>    
        <?php echo $this->Form->input('rpproducto', array('type' => 'hidden', 'name' => 'rpproducto', 'value' => $producto, 'id' => 'rpproducto_id'))?>
        <?php echo $this->Form->input('rpdeposito', array('type' => 'hidden', 'name' => 'rpdeposito', 'value' => $deposito, 'id' => 'rpdeposito_id'))?>
        <?php echo $this->Form->submit('Descargar',array('class'=>'btn btn-primary')); ?>
    </fieldset>
</form>
<br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Nuevo Cargue Inventario'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Productos'), array('controller' => 'productos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Producto'), array('controller' => 'productos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Depositos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Deposito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Impuestos'), array('controller' => 'impuestos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Impuesto'), array('controller' => 'impuestos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Tipos de Pago'), array('controller' => 'tipopagos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Tipo de Pago'), array('controller' => 'tipopagos', 'action' => 'add')); ?> </li>
	</ul>
</div>
