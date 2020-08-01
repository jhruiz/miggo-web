<?php $this->layout='inicio'; ?>
<div class="perfiles form">
<?php echo $this->Form->create('Perfile'); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Editar Perfil'); ?></b></h2></legend>
                <?php echo $this->Form->input('id'); ?>
                
                <div class="form-group form-inline">
                    <?php echo $this->Form->input('descripcion', array('label' => 'Nombre Perfil', 'class' => 'form-control', 'placeholder' => 'Nombre del Perfil')); ?>
                </div>
                <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId));	?>
	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Perfiles'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
