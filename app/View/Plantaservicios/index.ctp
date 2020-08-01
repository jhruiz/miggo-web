<?php $this->layout='inicio'; ?>
<div class="plantaservicios index">             
            
	<legend><h2><b><?php echo __('Plantas de Servicios'); ?></b></h2></legend>
        
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                                <th><?php echo $this->Paginator->sort('descripcion', 'Planta de Servicios'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($plantaservicios as $plantaservicio): ?>
                <tr>
                        <td><?php echo h($plantaservicio['Plantaservicio']['descripcion']); ?>&nbsp;</td>
                        <td class="actions">
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver planta servicios', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $plantaservicio['Plantaservicio']['id']))); ?>
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar planta servicios', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $plantaservicio['Plantaservicio']['id']))); ?>                   
                            <?php
                            echo $this->Form->postLink(                        
                              $this->Html->image('png/list-2.png', array('title' => 'Eliminar Planta Servicio', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                              array('action' => 'delete', $plantaservicio['Plantaservicio']['id']), //url
                              array('escape' => false), //el escape
                              __('Está seguro que desea eliminar la planta de servicios %s?', $plantaservicio['Plantaservicio']['descripcion']) //la confirmacion
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
		<li><?php echo $this->Html->link(__('Nueva Planta de Servicio'), array('action' => 'add')); ?></li>
	</ul>
</div>
