<?php $this->layout='inicio'; ?>
<div class="categorias index">
    
        <?php echo $this->Form->create('Categorias',array('action'=>'search','method'=>'post'));?>
            <legend><h2><b><?php echo __('Buscar Categorías'); ?></b></h2></legend>  
            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '12', 'id' => 'menuvert'))?>    
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group ">  
                        <label>Nombre</label><br>                          
                        <input name="nombre" id="nombre" class="form-control" placeholder="Nombre de la Categoría" type="text">
                    </div>             
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
        
	<legend><h2><b><?php echo __('Categorías'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">          
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                                <th><?php echo $this->Paginator->sort('descripcion', 'Nombre'); ?></th>
                                <th><?php echo $this->Paginator->sort('mostrarencatalogo', 'Ver en Catálogo'); ?></th>
                                <th><?php echo $this->Paginator->sort('servicio', 'Es servicio'); ?></th>
                                <th><?php echo $this->Paginator->sort('created', 'Fecha'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($categorias as $categoria): ?>
                <tr>
                        <td><?php echo h($categoria['Categoria']['descripcion']); ?>&nbsp;</td>
                        <td><?php if($categoria['Categoria']['mostrarencatalogo']=='1'){ echo "SI";}else{ echo "NO";} ?>&nbsp;</td>
                        <td><?php if($categoria['Categoria']['servicio'] =='1'){ echo "SI";}else{ echo "NO";} ?>&nbsp;</td>
                        <td><?php echo h($categoria['Categoria']['created']); ?>&nbsp;</td>
                        <td class="actions">                            
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Categoría', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $categoria['Categoria']['id']))); ?>
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Categoría', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $categoria['Categoria']['id']))); ?>                   
                            <?php
                                echo $this->Form->postLink(                        
                                  $this->Html->image('png/list-2.png', array('title' => 'Eliminar Categoría', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                                  array('action' => 'delete', $categoria['Categoria']['id']), //url
                                  array('escape' => false), //el escape
                                  __('Está seguro que desea eliminar la categoría %s?', $categoria['Categoria']['descripcion']) //la confirmacion
                                );                            
                            ?>                            
                        </td>
                </tr>
                <?php endforeach; ?>
                </table>
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
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Nueva Categoría'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Productos'), array('controller' => 'productos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Producto'), array('controller' => 'productos', 'action' => 'add')); ?> </li>
	</ul>
</div>
