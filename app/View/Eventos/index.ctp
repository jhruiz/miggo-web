<?php $this->layout='inicio'; ?>
<div class="eventos index">
	<legend><h2><b><?php echo __('Eventos'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                                <th><?php echo h('Tipo Evento'); ?></th>
                                <th><?php echo h('Responsable'); ?></th>
                                <th><?php echo h('Fecha Evento'); ?></th>
                                <th><?php echo h('Cliente'); ?></th>
                                <th><?php echo h('Estado'); ?></th>
                                <th><?php echo h('Teléfono'); ?></th>
                                <th><?php echo h('Placa'); ?></th>
                                <th><?php echo h('Descripción'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($eventos as $evento): ?>
                <tr>
                        <td><?php echo h($tipoEventos[$evento['Evento']['tipoevento_id']]); ?>&nbsp;</td>
                        <td><?php echo h($usuarios[$evento['Evento']['usuario_id']]); ?>&nbsp;</td>
                        <td><?php echo h($evento['Evento']['fecha']); ?>&nbsp;</td>
                        <td><?php echo h($evento['Evento']['cliente']); ?>&nbsp;</td>
                        <td><?php echo h($estados[$evento['Evento']['estadoalerta_id']]); ?>&nbsp;</td>
                        <td><?php echo h($evento['Evento']['telefono']); ?>&nbsp;</td>
                        <td><?php echo h($evento['Evento']['placa']); ?>&nbsp;</td>
                        <td><?php echo h($evento['Evento']['descripcion']); ?>&nbsp;</td>
                        <td class="actions">
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Evento', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $evento['Evento']['id']))); ?>
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
</div>
