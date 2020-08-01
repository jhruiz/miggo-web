<?php $this->layout='inicio'; ?>
<div class="categorias ">
<legend><h2><b><?php echo __('Categoría'); ?></b></h2></legend>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '12', 'id' => 'menuvert'))?>
	<dl>
		<dt><?php echo __('Descripción'); ?></dt>
		<dd>
			<?php echo h($categoria['Categoria']['descripcion']); ?>
			&nbsp;
                </dd><br>
		<dt><?php echo __('Mostrar en Catálogo'); ?></dt>
		<dd>
			<?php echo h($categoria['Categoria']['mostrarencatalogo'] == '1' ? "SI" : "NO"); ?>
			&nbsp;
                </dd><br>
		<dt><?php echo __('Es Servicio'); ?></dt>
		<dd>
			<?php echo h($categoria['Categoria']['servicio'] == '1' ? "SI" : "NO"); ?>
			&nbsp;
                </dd><br>
		<dt><?php echo __('Fecha'); ?></dt>
		<dd>
			<?php echo h($categoria['Categoria']['created']); ?>
			&nbsp;
		</dd>
	</dl>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Categoría'), array('action' => 'edit', $categoria['Categoria']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Categorías'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Categoría'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Productos'), array('controller' => 'productos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Producto'), array('controller' => 'productos', 'action' => 'add')); ?> </li>
	</ul>
</div>