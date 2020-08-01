<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('compras/index'));?>
<div class="compras index">
    
            <?php echo $this->Form->create('Compras',array('action'=>'search','method'=>'post', 'class' => 'form-inline'));?>
            <legend><h2><b><?php echo __('Buscar Compras'); ?></b></h2></legend>      
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group ">  
                    <label for="fechaDesde">Desde</label><br>
                    <input name="fechaDesde" class="date form-control" placeholder="Desde" type="text" id="fechaDesde" value="" autocomplete="off">
                    </div>             
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="fechaHasta">Hasta</label><br>
                        <input name="fechaHasta" class="date form-control" placeholder="Hasta" type="text" id="fechaHasta" value="" autocomplete="off">                        
                    </div>            
                </div>                
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="numFactura">Num. Factura</label><br>
                        <input name="numFactura" class="form-control" placeholder="Número Factura" type="text" id="numFactura" value="" autocomplete="off">                        
                    </div>            
                </div>               

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="usuario_id">Usuario</label><br>
                        <select name="usuario_id" id="usuario_id" class="form-control">
                            <option value="">Seleccione</option>
                          <?php foreach ($listUsr as $key => $val){?>
                            <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                          <?php } ?>
                        </select>                    
                    </div>            
                </div>  
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="proveedore_id">Proveedor</label><br>
                        <select name="proveedore_id" id="proveedore_id" class="form-control">
                            <option value="">Seleccione</option>
                          <?php foreach ($listProv as $key => $val){?>
                            <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                          <?php } ?>
                        </select>                    
                    </div>            
                </div>                
            </div><br>        
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
        
        
	<legend><h2><b><?php echo __('Compras'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                    <tr>
                                    <th><?php echo h('Fecha'); ?></th>
                                    <th><?php echo h('Proveedor'); ?></th>
                                    <th><?php echo h('Usuario'); ?></th>
                                    <th><?php echo h('# Factura'); ?></th>
                                    <th class="actions"><?php echo __('Acciones'); ?></th>
                    </tr>
                    <?php foreach ($compras as $compra): ?>
                    <tr>
                            <td><?php echo h($compra['Compra']['fecha']); ?>&nbsp;</td>
                            <td><?php echo h($listProv[$compra['Compra']['proveedore_id']]); ?>&nbsp;</td>
                            <td><?php echo h($listUsr[$compra['Compra']['usuario_id']]); ?>&nbsp;</td>
                            <td><?php echo h($compra['Compra']['numerofactura']); ?>&nbsp;</td>
                            <td class="actions">
                                <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Ciudad', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $compra['Compra']['id']))); ?>
                                <?php
                                echo $this->Form->postLink(                        
                                  $this->Html->image('png/list-2.png', array('title' => 'Eliminar Ciudad', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                                  array('action' => 'delete', $compra['Compra']['id']), //url
                                  array('escape' => false), //el escape
                                  __('Está seguro que desea eliminar la compra %s?', $compra['Compra']['numerofactura']) //la confirmacion
                                ); 
                                ?>                                   
                            </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
        <?php echo $this->Form->create('Reporte',array( 'controller' => 'reportes','action'=>'descargarCompras')); ?>
            <fieldset>
                
                    <input type='hidden' name='proveedorId' value="<?php echo($proveedorId); ?>">
                    <input type='hidden' name='usuarioId' value="<?php echo($usuarioId); ?>">
                    <input type='hidden' name='numFactura' value="<?php echo($numFactura); ?>">
                    <input type='hidden' name='FDesde' value="<?php echo($FDesde); ?>">
                    <input type='hidden' name='FHasta' value="<?php echo($FHasta); ?>"> 
                    
                <div class="row">                
                    <div class="col-md-3">
                        <select name="type_tax" class="form-control">
                          <option value="1" selected>Todos</option> 
                          <option value="2">IVA</option>
                          <option value="3">RETEFUENTE</option>
                          <option value="4">RETEICA</option>
                        </select>                        
                    </div>
                    <div class="col-md-3">
                        <div class="form-group ">  
                        <?php echo $this->Form->submit('Generar Reporte',array('class'=>'btn btn-info')); ?>
                        </div>             
                    </div>                    
                    <div class="col-md-6">
                        &nbsp;
                    </div>
                </div>                                             
            </fieldset>
        </form>        
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
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Nueva Compra'), array('action' => 'add')); ?></li>
	</ul>
</div>
