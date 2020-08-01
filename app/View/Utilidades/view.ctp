<div class="utilidades view">
<h2><?php echo __('Utilidade'); ?></h2>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '34', 'id' => 'menuvert'))?>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($utilidade['Utilidade']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cargueinventario'); ?></dt>
		<dd>
			<?php echo $this->Html->link($utilidade['Cargueinventario']['id'], array('controller' => 'cargueinventarios', 'action' => 'view', $utilidade['Cargueinventario']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cantidad'); ?></dt>
		<dd>
			<?php echo h($utilidade['Utilidade']['cantidad']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Precioventa'); ?></dt>
		<dd>
			<?php echo h($utilidade['Utilidade']['precioventa']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($utilidade['Utilidade']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Utilidadbruta'); ?></dt>
		<dd>
			<?php echo h($utilidade['Utilidade']['utilidadbruta']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Utilidadporcentual'); ?></dt>
		<dd>
			<?php echo h($utilidade['Utilidade']['utilidadporcentual']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Empresa'); ?></dt>
		<dd>
			<?php echo $this->Html->link($utilidade['Empresa']['nombre'], array('controller' => 'empresas', 'action' => 'view', $utilidade['Empresa']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Utilidade'), array('action' => 'edit', $utilidade['Utilidade']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Utilidade'), array('action' => 'delete', $utilidade['Utilidade']['id']), null, __('Are you sure you want to delete # %s?', $utilidade['Utilidade']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Utilidades'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Utilidade'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cargueinventarios'), array('controller' => 'cargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargueinventario'), array('controller' => 'cargueinventarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Empresas'), array('controller' => 'empresas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Empresa'), array('controller' => 'empresas', 'action' => 'add')); ?> </li>
	</ul>
</div>
