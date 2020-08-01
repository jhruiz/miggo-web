<?php $this->layout='inicio'; ?>
<div class="categoriacompras view">
<legend><h2><?php echo __('Categoriacompra'); ?></h2></legend>
	<dl>		
		<dt><?php echo __('Categoría'); ?></dt>
		<dd>
			<?php echo h($categoriacompra['Categoriacompra']['descripcion']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<legend><h3><?php echo __('Acciones'); ?></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Categoría Compra'), array('action' => 'edit', $categoriacompra['Categoriacompra']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Categorías de Compra'), array('action' => 'index')); ?> </li>
	</ul>
</div>
