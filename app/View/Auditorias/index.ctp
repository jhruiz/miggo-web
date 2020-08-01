<?php $this->layout='inicio'; ?>
<div class="auditorias index">
	<legend><h2><b><?php echo __('Auditorias'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">          
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                                <th><?php echo $this->Paginator->sort('usuario_id'); ?></th>
                                <th><?php echo $this->Paginator->sort('Acción'); ?></th>
                                <th><?php echo $this->Paginator->sort('Descripción'); ?></th>
                                <th><?php echo $this->Paginator->sort('Fecha'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($auditorias as $auditoria): ?>
                <tr>
                        <td>
                                <?php echo $this->Html->link($auditoria['Usuario']['nombre'], array('controller' => 'usuarios', 'action' => 'view', $auditoria['Usuario']['id'])); ?>
                        </td>
                        <td><?php echo h($auditoria['Auditoria']['accion']); ?>&nbsp;</td>
                        <td><?php echo h($auditoria['Auditoria']['descripcion']); ?>&nbsp;</td>
                        <td><?php echo h($auditoria['Auditoria']['created']); ?>&nbsp;</td>
                        <td class="actions">
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Auditoria', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $auditoria['Auditoria']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
