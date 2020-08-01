<?php $this->layout='inicio'; ?>
<div class="ciudades form">
<?php echo $this->Form->create('Ciudade', array('class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Editar Ciudad'); ?></b></h2></legend>
	<?php
		echo $this->Form->input('id');
	?>		
        <div class="col-md-12" style="margin-bottom: 20px;">                         
            <?php echo $this->Form->input('id'); ?>
            
            <div class="form-group">
                <label>Nombre</label>
                <?php echo $this->Form->input('descripcion', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Nombre de la Ciudad')); ?>
            </div>

            <div class="form-group">
                <label>Pais</label>
                <?php echo $this->Form->input('paise_id', array('label' => '', 'class' => 'form-control')); ?>
            </div>
        </div>			

	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Ciudades'), array('action' => 'index')); ?></li>
	</ul>
</div>
