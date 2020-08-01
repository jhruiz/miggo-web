<?php 
$this->layout = 'inicio'; 
echo $this->Html->script('semaforos/semaforos.js');
?>
<div class="semaforos index">
        <legend><h2><b><?php echo __('Semáforos'); ?></b></h2></legend>     
        <table class="table table-striped">
	<tr>
			<th><?php echo $this->Paginator->sort('rangoinicial', 'Rango Inicial'); ?></th>
			<th><?php echo $this->Paginator->sort('rangofinal', 'Rango Final'); ?></th>
			<th><?php echo $this->Paginator->sort('color'); ?></th>
			<th class="actions"><?php echo __('Acciones'); ?></th>
	</tr>
	<?php foreach ($semaforos as $semaforo): ?>
	<tr>
		<td><?php echo h($semaforo['Semaforo']['rango_inicial']); ?>&nbsp;</td>
		<td><?php echo h($semaforo['Semaforo']['rango_final']); ?>&nbsp;</td>
		<td bgcolor="#<?php echo h($semaforo['Semaforo']['color']); ?>" ><?php echo "#".h($semaforo['Semaforo']['color']); ?>&nbsp;</td>
                        <td class="actions">
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Semáforo', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $semaforo['Semaforo']['id']))); ?>
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Semáforo', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $semaforo['Semaforo']['id']))); ?>                        
                        </td>
	</tr>
<?php endforeach; ?>
	</table>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('Anterior'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('Siguiente') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Nuevo Semáforo'), array('action' => 'add')); ?></li>
	</ul>
</div>
