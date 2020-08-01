<?php $this->layout='inicio'; ?>
<div class="cloudmenusPerfiles form">
<?php echo $this->Form->create('CloudmenusPerfile'); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Agregar Menú - Perfil'); ?></b></h2></legend>
                <div class="form-group form-inline"> 
                    <?php echo $this->Form->input('perfile_id', array('label' => 'Perfil', 'class' => 'form-control')); ?>                   
                </div>
                
                <div class="form-group form-inline"> 
                    <?php echo $this->Form->input('cloudmenu_id', array('label' => 'Menú', 'class' => 'form-control')); ?>                   
                </div>

	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>   
</div><br>
<div class="actions">
    <legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>

		<li><?php echo $this->Html->link(__('Lista Menús - Perfiles'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Menús'), array('controller' => 'cloudmenus', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Menú'), array('controller' => 'cloudmenus', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Perfiles'), array('controller' => 'perfiles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Perfil'), array('controller' => 'perfiles', 'action' => 'add')); ?> </li>
	</ul>
</div>
