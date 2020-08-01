<?php $this->layout='inicio'; ?>
<div class="regimenes form">
<?php echo $this->Form->create('Regimene'); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Agregar Régimen'); ?></b></h2></legend>
                <div class="form-group form-inline"> 
                    <?php echo $this->Form->input('descripcion', array('label' => 'Nombre', 'class' => 'form-control', 'placeholder' => 'Nombre del Régimen')); ?>                   
                </div>	
	</fieldset><br>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>

		<li><?php echo $this->Html->link(__('Lista Régimen'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Depósitos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Depósito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
	</ul>
</div>
