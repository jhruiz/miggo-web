<?php $this->layout='inicio'; ?>
<div class="usuarios index">
    
            <?php echo $this->Form->create('Usuarios',array('action'=>'search','method'=>'post'));?>
            <legend><h2><b><?php echo __('Buscar Usuarios'); ?></b></h2></legend> 
            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '25', 'id' => 'menuvert'))?>     
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group ">  
                        <label>Nombre</label><br>                          
                        <input name="nombre" id="nombre" class="form-control" placeholder="Nombre del Usuario" type="text">
                    </div>             
                </div>  
                
                <div class="col-md-3">
                    <div class="form-group ">  
                        <label>Identificacion</label><br>                          
                        <input name="identificacion" id="identificacion" class="form-control" placeholder="Identificación del Usuario" type="text">
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
        
	<legend><h2><b><?php echo __('Usuarios'); ?></b></h2></legend>
        
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                <tr>
                                <th><?php echo $this->Paginator->sort('nombre'); ?></th>
                                <th><?php echo $this->Paginator->sort('identificacion'); ?></th>
                                <th><?php echo $this->Paginator->sort('username'); ?></th>
                                <th><?php echo $this->Paginator->sort('perfile_id'); ?></th>
                                <th><?php echo $this->Paginator->sort('estado_id'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                        <td><?php echo h($usuario['Usuario']['nombre']); ?>&nbsp;</td>
                        <td><?php echo h($usuario['Usuario']['identificacion']); ?>&nbsp;</td>
                        <td><?php echo h($usuario['Usuario']['username']); ?>&nbsp;</td>
                        <td>
                                <?php echo $this->Html->link($usuario['Perfile']['descripcion'], array('controller' => 'perfiles', 'action' => 'view', $usuario['Perfile']['id'])); ?>
                        </td>
                        <td>
                                <?php echo $usuario['Estado']['descripcion']; ?>
                        </td>
                        <td class="actions">
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Usuario', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $usuario['Usuario']['id']))); ?>
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Usuario', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $usuario['Usuario']['id']))); ?>                                                                        
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
	<?php echo $this->Paginator->prev('< ' . __('Anterior '), array(), null, array('class' => 'prev disabled')); ?>
	<?php echo $this->Paginator->numbers(array('separator' => ' || ')); ?>
	<?php echo $this->Paginator->next(__(' Siguiente') . ' >', array(), null, array('class' => 'next disabled')); ?>

	</div>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Notas'), array('controller' => 'anotaciones', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Nota'), array('controller' => 'anotaciones', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Inventarios'), array('controller' => 'cargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Cargue Inventario'), array('controller' => 'cargueinventarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Depósitos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Depósito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Descargue Inventarios'), array('controller' => 'descargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Descargue Inventario'), array('controller' => 'descargueinventarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Categorías'), array('controller' => 'categorias', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Categoría'), array('controller' => 'categorias', 'action' => 'add')); ?> </li>
	</ul>
</div>
