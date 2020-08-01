<?php $this->layout='inicio'; ?>
<div class="licencias index">
	<legend><h2><b><?php echo __('Licencias'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                                <th><?php echo $this->Paginator->sort('cantidaddias', 'Días de Licencia'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($licencias as $licencia): ?>
                <tr>
                        <td><?php echo h($licencia['Licencia']['cantidaddias']); ?>&nbsp;</td>
                        <td class="actions">
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Licencia', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $licencia['Licencia']['id']))); ?>
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Licencia', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $licencia['Licencia']['id']))); ?>                   
                            <?php
                            echo $this->Form->postLink(                        
                              $this->Html->image('png/list-2.png', array('title' => 'Eliminar Licencia', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                              array('action' => 'delete', $licencia['Licencia']['id']), //url
                              array('escape' => false), //el escape
                              __('Está seguro que desea eliminar la licencia %s?', $licencia['Licencia']['cantidaddias']) //la confirmacion
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
		<li><?php echo $this->Html->link(__('Nueva Licencia'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
