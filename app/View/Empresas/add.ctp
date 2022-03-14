<?php $this->layout = 'inicio';?>
<div class="empresas form">
<?php echo $this->Form->create('Empresa', array('type' => 'post', 'class' => 'form-horizontal')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Agregar Empresa'); ?></b></h2></legend>
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '41', 'id' => 'menuvert')) ?>

                <div class="form-group form-inline">
                    <?php echo $this->Form->input('nombre', array('class' => 'form-control', 'type' => 'text', 'placeholder' => 'Nombre Empresa')); ?>
                </div>

                <div class="form-group form-inline">
                    <?php echo $this->Form->input('nit', array('class' => 'form-control', 'placeholder' => 'Nit Empresa')); ?>
                </div>

                <div class="form-group form-inline">
                    <?php echo $this->Form->input('direccion', array('label' => 'Dirección', 'class' => 'form-control', 'placeholder' => 'Dirección Empresa')); ?>
                </div>

                <div class="form-group form-inline">
                    <?php echo $this->Form->input('telefono1', array('label' => 'Teléfono 1', 'class' => 'form-control', 'placeholder' => 'Teléfono 1 Empresa')); ?>
                </div>

                <div class="form-group form-inline">
                    <?php echo $this->Form->input('telefono2', array('label' => 'Teléfono 2', 'class' => 'form-control', 'placeholder' => 'Teléfono 2 Empresa')); ?>
                </div>

                <div class="form-group form-inline">
                    <?php echo $this->Form->input('email', array('label' => 'E-mail', 'class' => 'form-control', 'placeholder' => 'Correo Empresa')); ?>
                </div>

                <div class="form-group form-inline">
                    <?php echo $this->Form->input('representantelegal', array('label' => 'Representante Legal', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre Representante')); ?>
                </div>

                <div class="form-group form-inline">
                    <?php echo $this->Form->input('ciudade_id', array('label' => 'Ciudad', 'class' => 'form-control')); ?>
                </div>

                <div class="form-group form-inline">
                    <?php echo $this->Form->input('imagen', array('type' => 'file')); ?>
                    <p class="help-block">Máximo 1MB</p>
                </div>
	</fieldset>
<?php echo $this->Form->submit('Guardar', array('class' => 'btn btn-primary')); ?>
</div>
