<?php $this->layout = 'inicio'; ?>
<div class="semaforos">
    
<legend><h2><?php echo __('Semáforo'); ?></h2></legend>
	<dl>
		<dt><?php echo __('Rango Inicial'); ?></dt>
		<dd>
			<?php echo h($semaforo['Semaforo']['rango_inicial']); ?>
			&nbsp;
		</dd><br>
		<dt><?php echo __('Rango Final'); ?></dt>
		<dd>
			<?php echo h($semaforo['Semaforo']['rango_final']); ?>
			&nbsp;
		</dd><br>
		<dt><?php echo __('Color'); ?></dt>
		<dd>
                    <?php echo h($semaforo['Semaforo']['color']); ?>
                    <div style="border-width: 4px; border-radius: 25px; width: 20px;background: #<?php echo $semaforo['Semaforo']['color'];?>">&nbsp;</div>			
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<legend><h3><?php echo __('Acciones'); ?></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Semáforo'), array('action' => 'edit', $semaforo['Semaforo']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Semáforos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Semáforo'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Bandejas'), array('controller' => 'bandejas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Bandeja'), array('controller' => 'bandejas', 'action' => 'add')); ?> </li>
	</ul>
</div>