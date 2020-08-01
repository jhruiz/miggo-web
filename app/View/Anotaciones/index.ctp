<?php $this->layout='inicio'; ?>
<div class="anotaciones index">
	<legend><h2><b><?php echo __('Notas'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>			
                                <th><?php echo $this->Paginator->sort('descripcion', 'Nota'); ?></th>
                                <th><?php echo $this->Paginator->sort('usuario_id'); ?></th>
                                <th><?php echo $this->Paginator->sort('cliente_id'); ?></th>
                                <th><?php echo $this->Paginator->sort('created', 'Fecha'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($anotaciones as $anotacione): ?>
                <tr>
                        <td><?php echo h($anotacione['Anotacione']['descripcion']); ?>&nbsp;</td>
                        <td>
                                <?php echo h($anotacione['Usuario']['nombre']); ?>
                        </td>
                        <td>
                                <?php echo $this->Html->link($anotacione['Cliente']['nombre'], array('controller' => 'clientes', 'action' => 'view', $anotacione['Cliente']['id'])); ?>
                        </td>
                        <td><?php echo h($anotacione['Anotacione']['created']); ?>&nbsp;</td>
                        <td class="actions">                            
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Nota', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $anotacione['Anotacione']['id']))); ?>
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Nota', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $anotacione['Anotacione']['id']))); ?>                   
                            <?php
                                echo $this->Form->postLink(                        
                                  $this->Html->image('png/list-2.png', array('title' => 'Eliminar Nota', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                                  array('action' => 'delete', $anotacione['Anotacione']['id']), //url
                                  array('escape' => false), //el escape
                                  __('Está seguro que desea eliminar la nota %s?', $anotacione['Anotacione']['descripcion']) //la confirmacion
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
		<li><?php echo $this->Html->link(__('Nueva Nota'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
	</ul>
</div>


