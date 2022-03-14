<?php $this->layout = 'inicio';?>
<div class="usuarios form">
<?php echo $this->Form->create('Usuario', array('type' => 'post', 'class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Editar Usuario'); ?></b></h2></legend>
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '25', 'id' => 'menuvert')) ?>
                <?php echo $this->Form->input('id'); ?>


                <div class="col-md-12" style="margin-bottom: 20px;">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="UsuarioNombre">Nombre</label>
                            <?php echo $this->Form->input('nombre', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Nombre del Usuario')); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="UsuarioIdentificacion">CC/Nit</label>
                            <?php echo $this->Form->input('identificacion', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Identificacion del Usuario')); ?>
                        </div>
                    </div>

                </div>


                <div class="col-md-12" style="margin-bottom: 20px;">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="UsuarioUsername">User Name</label>
                            <?php echo $this->Form->input('username', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Login del Usuario')); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo $this->Form->input('imagen', array('label' => 'Imagen', 'type' => 'file')); ?>
                            <p class="help-block">Máximo 1MB</p>
                        </div>
                    </div>

                </div>


                <div class="col-md-12" style="margin-bottom: 10px;">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="UsuarioPerfileId">Perfil</label>
                            <?php echo $this->Form->input('perfile_id', array('label' => '', 'class' => 'form-control')); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="UsuarioEstadoId">Estado</label>
                            <?php echo $this->Form->input('estado_id', array('label' => '', 'class' => 'form-control')); ?>
                        </div>
                    </div>

                </div>

                <div class="form-group form-inline">
                    <?php echo $this->Form->input('estadologin', array('type' => 'hidden', 'value' => '0')); ?>
                    <?php echo $this->Form->input('intentos', array('type' => 'hidden', 'value' => '0')); ?>
                    <?php if ($perfilId == '1') {echo $this->Form->input('empresa_id', array('class' => 'form-control'));} else {echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresas));}?>
                </div>
	</fieldset>
<?php echo $this->Form->submit('Guardar', array('class' => 'btn btn-primary')); ?>
</div>