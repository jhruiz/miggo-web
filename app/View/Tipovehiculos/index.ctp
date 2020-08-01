<?php $this->layout='inicio'; ?>
<div class="tipovehiculos index">
            
	<legend><h2><b><?php echo __('Tipos de Vehículos'); ?></b></h2></legend>
        
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                                <th><?php echo $this->Paginator->sort('descripcion', 'Tipo de Vehcíulo'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($tipovehiculos as $tipovehiculo): ?>
                <tr>
                        <td><?php echo h($tipovehiculo['Tipovehiculo']['descripcion']); ?>&nbsp;</td>
                        <td class="actions">
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Tipo Vehículo', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $tipovehiculo['Tipovehiculo']['id']))); ?>
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Tipo de Vehículo', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $tipovehiculo['Tipovehiculo']['id']))); ?>                   
                            <?php
                            echo $this->Form->postLink(                        
                              $this->Html->image('png/list-2.png', array('title' => 'Eliminar Tipo de Vehículo', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                              array('action' => 'delete', $tipovehiculo['Tipovehiculo']['id']), //url
                              array('escape' => false), //el escape
                              __('Está seguro que desea eliminar el Tipo de Vehículo %s?', $tipovehiculo['Tipovehiculo']['descripcion']) //la confirmacion
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
		<li><?php echo $this->Html->link(__('Nuevo Tipo Vehículo'), array('action' => 'add')); ?></li>
        </ul>
</div>
