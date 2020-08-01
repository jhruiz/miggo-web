<?php echo $this->Html->css('colorpicker'); ?>
<?php echo $this->Html->script('colorpicker'); ?>
<?php echo $this->Html->script('semaforos/semaforos'); ?>
<?php $this->layout = 'inicio'; ?>

<div class="semaforos form">
<?php echo $this->Form->create('Semaforo', array('class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Agregar Semáforo'); ?></b></h2></legend>
                <div class="form-group">
                    <label for="SemaforoRangoInicial">Rango Inicial</label>
                    <?php echo $this->Form->input('rango_inicial', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Rango Inicial', 'autocomplete' => 'off')); ?>
                </div>        
                
                <div class="form-group">
                    <label for="SemaforoRangoFinal">Rango Final</label>
                    <?php echo $this->Form->input('rango_final', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Rango Final', 'autocomplete' => 'off')); ?>
                </div>        
                
                <div class="form-group">
                    <label for="SemaforoColor">Color</label>
                    <?php echo $this->Form->input('color', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Color Semáforo', 'autocomplete' => 'off')); ?>
                </div>        
	</fieldset><br>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div>
