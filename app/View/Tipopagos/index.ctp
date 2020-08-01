<?php $this->layout='inicio'; ?>
<div class="tipopagos index">
	<legend><h2><b><?php echo __('Tipo de Pago'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                                <th><?php echo $this->Paginator->sort('descripcion', 'Nombre Tipo de Pago'); ?></th>
                                <th><?php echo $this->Paginator->sort('estado_id'); ?></th>
                                <th><?php echo $this->Paginator->sort('cuenta_id'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($tipopagos as $tipopago): ?>
                <tr>
                        <td><?php echo h($tipopago['Tipopago']['descripcion']); ?>&nbsp;</td>
                        <td><?php echo h($tipopago['Estado']['descripcion']); ?></td>
                        <td><?php echo h(!empty($listCtas[$tipopago['Tipopago']['cuenta_id']]) ? $listCtas[$tipopago['Tipopago']['cuenta_id']] : "SIN ASIGNAR");?></td>
                        <td class="actions">
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Tipo de Pago', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $tipopago['Tipopago']['id']))); ?>
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Tipo de Pago', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $tipopago['Tipopago']['id']))); ?>                   
                            <?php
                            echo $this->Form->postLink(                        
                              $this->Html->image('png/list-2.png', array('title' => 'Eliminar Tipo de Pago', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                              array('action' => 'delete', $tipopago['Tipopago']['id']), //url
                              array('escape' => false), //el escape
                              __('Está seguro que desea eliminar el Tipo de Pago %s?', $tipopago['Tipopago']['descripcion']) //la confirmacion
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
		<li><?php echo $this->Html->link(__('Nuevo Tipo Pago'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Cargue Inventarios'), array('controller' => 'cargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cargue Inventario'), array('controller' => 'cargueinventarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Descargue Inventarios'), array('controller' => 'descargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Descargue Inventario'), array('controller' => 'descargueinventarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
