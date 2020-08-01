<?php $this->layout='inicio'; ?>
<div class="usuarios form">
<?php echo $this->Form->create('Usuario', array('type' => 'file', 'class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Agregar Usuario'); ?></b></h2></legend>
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '25', 'id' => 'menuvert'))?>
                
                <div class="col-md-12" style="margin-bottom: 20px">
                    <div class="form-group col-md-4">
                        <label for="UsuarioNombre">Nombre</label>
                        <?php echo $this->Form->input('nombre', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Nombre del Usuario')); ?>
                    </div>                                    

                    <div class="form-group col-md-4">
                        <label for="UsuarioIdentificacion">CC/Nit</label>
                        <?php echo $this->Form->input('identificacion', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Identificacion del Usuario')); ?>
                    </div>                

                    <div class="form-group col-md-4">
                        <label for="UsuarioUsername">Username</label>
                        <?php echo $this->Form->input('username', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Login del Usuario')); ?>
                    </div>                
                </div>
                
                <div class="col-md-12" style="margin-bottom: 20px">                    
                    <div class="form-group col-md-4"> 
                        <?php echo $this->Form->input('imagen', array('type' => 'file')); ?>
                        <p class="help-block">Máximo 1MB</p>
                    </div>                

                    <div class="form-group col-md-4"> 
                        <label for="UsuarioPerfileId">Perfil</label>
                        <?php echo $this->Form->input('perfile_id', array('label' => '', 'class' => 'form-control')); ?>
                    </div> 
                    
                    <div class="form-group col-md-4"> 
                        <label for="UsuarioEstadoId">Estado</label>
                        <?php echo $this->Form->input('estado_id', array('label' => '', 'class' => 'form-control')); ?>
                    </div>                     
                </div>
                
                <div class="col-md-12" style="margin-bottom: 20px">

                    <div class="form-group col-md-4"> 
                        <label for="UsuarioPassword">Password</label>
                        <?php echo $this->Form->input('password', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Contraseña del Usuario')); ?>
                    </div> 

                    <div class="form-group col-md-4"> 
                        <label for="repetirPass">Repetir Password</label>
                        <?php echo $this->Form->input(null, array('label' => '', 'id' => 'repetirPass', 'type' => 'password', 'class' => 'form-control', 'placeholder' => 'Repetir Contraseña')); ?>
                    </div> 

                </div>                            
	                 
                    <?php echo $this->Form->input('estadologin', array('type' => 'hidden', 'value' => '0')); ?>
                    <?php echo $this->Form->input('intentos', array('type' => 'hidden', 'value' => '0')); ?>
                    <?php if($perfilId == '1'){echo $this->Form->input('empresa_id', array('class' => 'form-control'));}else{echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresas));} ?>                                       	                	                

	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>  
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>

		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Notas'), array('controller' => 'anotaciones', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Nota'), array('controller' => 'anotaciones', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Inventarios'), array('controller' => 'cargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Cargue Inventario'), array('controller' => 'cargueinventarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Depósitos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Depósito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Descargue Inventarios'), array('controller' => 'descargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Descargue Inventario'), array('controller' => 'descargueinventarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Categorías'), array('controller' => 'categorias', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Categoría'), array('controller' => 'categorias', 'action' => 'add')); ?> </li>
	</ul>
</div>
