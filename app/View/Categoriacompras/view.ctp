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
