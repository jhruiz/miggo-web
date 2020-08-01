<?php $this->layout='inicio'; ?>
<div class="categorias form">
<?php echo $this->Form->create('Categoria', array('class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Editar Categoría'); ?></b></h2></legend>
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '12', 'id' => 'menuvert'))?>
                <?php echo $this->Form->input('id'); ?>
                <?php echo $this->Form->input('usuario_id', array('type' => 'hidden', 'value' => $usuarioId)); ?>
                
                <div class="row">
                    <div class="form-group">
                        <label for="CategoriaDescripcion">Nombre</label>
                        <?php echo $this->Form->input('descripcion', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre de la Categoría')); ?>
                    </div>
                </div> 
                
                <div class="row">
                    <div class="form-group">
                        <label for="CategoriaMostrarencatalogo">Mostrar en Catálogo</label>
                        <?php echo $this->Form->input('mostrarencatalogo', array('label' => '', 'type' => 'checkbox', 'class' => 'form-control')); ?>
                    </div>
                </div> 
                
                <div class="row">
                    <div class="form-group">
                        <label for="CategoriaServicio">Es servicio</label>
                        <?php echo $this->Form->input('servicio', array('label' => '', 'type' => 'checkbox', 'class' => 'form-control')); ?>
                    </div>
                </div>                 
                                       
	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Categorías'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Productos'), array('controller' => 'productos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Producto'), array('controller' => 'productos', 'action' => 'add')); ?> </li>
	</ul>
</div>
