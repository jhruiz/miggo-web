<?php $this->layout='inicio'; ?>
<div class="empresas form">
<?php echo $this->Form->create('Empresa', array('type' => 'file', 'class' => 'form-horizontal')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Agregar Empresa'); ?></b></h2></legend>
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '41', 'id' => 'menuvert'))?>
                
                <div class="form-group form-inline">
                    <?php echo $this->Form->input('nombre', array('class' => 'form-control', 'type' => 'text', 'placeholder' => 'Nombre Empresa')); ?>
                </div>   
                
                <div class="form-group form-inline">
                    <?php echo $this->Form->input('nit', array('class' => 'form-control', 'placeholder' => 'Nit Empresa')); ?>
                </div>   

                <div class="form-group form-inline">
                    <?php echo $this->Form->input('direccion', array('label' => 'Dirección','class' => 'form-control', 'placeholder' => 'Dirección Empresa')); ?>
                </div>   

                <div class="form-group form-inline">
                    <?php echo $this->Form->input('telefono1', array('label' => 'Teléfono 1','class' => 'form-control', 'placeholder' => 'Teléfono 1 Empresa')); ?>
                </div>   

                <div class="form-group form-inline">
                    <?php echo $this->Form->input('telefono2', array('label' => 'Teléfono 2','class' => 'form-control', 'placeholder' => 'Teléfono 2 Empresa')); ?>
                </div>   
 
                <div class="form-group form-inline">
                    <?php echo $this->Form->input('email', array('label' => 'E-mail','class' => 'form-control', 'placeholder' => 'Correo Empresa')); ?>
                </div>   

                <div class="form-group form-inline">
                    <?php echo $this->Form->input('representantelegal', array('label' => 'Representante Legal', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre Representante')); ?>
                </div>   

                <div class="form-group form-inline">
                    <?php echo $this->Form->input('ciudade_id', array('label' => 'Ciudad','class' => 'form-control')); ?>
                </div>   

                <div class="form-group form-inline"> 
                    <?php echo $this->Form->input('imagen', array('type' => 'file')); ?>
                    <p class="help-block">Máximo 1MB</p>
                </div>                 
	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?> 
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>

		<li><?php echo $this->Html->link(__('Lista Empresas'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Ciudades'), array('controller' => 'ciudades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Ciudade'), array('controller' => 'ciudades', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Impuestos'), array('controller' => 'impuestos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Impuesto'), array('controller' => 'impuestos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Tipo Pagos'), array('controller' => 'tipopagos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Tipo Pago'), array('controller' => 'tipopagos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Depósitos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Depósito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Categorias'), array('controller' => 'categorias', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Categoria'), array('controller' => 'categorias', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Tipo Depósitos'), array('controller' => 'tipodepositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Tipo Depósito'), array('controller' => 'tipodepositos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Perfiles'), array('controller' => 'perfiles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Perfile'), array('controller' => 'perfiles', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
