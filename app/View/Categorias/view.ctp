<?php $this->layout = 'inicio';?>
<div class="categorias ">
<legend><h2><b><?php echo __('Categoría'); ?></b></h2></legend>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '12', 'id' => 'menuvert')) ?>
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
</div>