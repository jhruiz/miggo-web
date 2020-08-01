<?php $this->layout='inicio'; ?>
<div class="empresas form">
<?php echo $this->Form->create('Empresa', array('type' => 'file', 'class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Editar Empresa'); ?></b></h2></legend>   
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '41', 'id' => 'menuvert'))?>             
                    <?php echo $this->Form->input('id');?>
                
                <div class="col-md-12" style="margin-bottom: 20px;">                    

                    <div class="form-group col-md-3">
                        <label for="EmpresaNombre">Nombre</label>
                        <?php echo $this->Form->input('nombre', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre Empresa')); ?>
                    </div>   

                    <div class="form-group col-md-3">
                        <label for="EmpresaNit">Nit</label>
                        <?php echo $this->Form->input('nit', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Nit Empresa')); ?>
                    </div>   

                    <div class="form-group col-md-3">
                        <label for="EmpresaDireccion">Dirección</label>
                        <?php echo $this->Form->input('direccion', array('label' => '','class' => 'form-control', 'placeholder' => 'Dirección Empresa')); ?>
                    </div>   

                </div>
                
                <div class="col-md-12" style="margin-bottom: 20px;">
                                        
                    <div class="form-group col-md-3">
                        <label for="EmpresaTelefono1">Teléfono 1</label>
                        <?php echo $this->Form->input('telefono1', array('label' => '','class' => 'form-control', 'placeholder' => 'Teléfono 1 Empresa')); ?>
                    </div>   

                    <div class="form-group col-md-3">
                        <label for="EmpresaTelefono2">Teléfono 2</label>
                        <?php echo $this->Form->input('telefono2', array('label' => '','class' => 'form-control', 'placeholder' => 'Teléfono 2 Empresa')); ?>
                    </div>   

                    <div class="form-group col-md-3">
                        <label for="EmpresaEmail">E-mail</label>
                        <?php echo $this->Form->input('email', array('label' => '','class' => 'form-control', 'placeholder' => 'Correo Empresa')); ?>
                    </div>   
                    
                </div>
                
                <div class="col-md-12" style="margin-bottom: 20px;">
                                        
                    <div class="form-group col-md-3">
                        <label for="EmpresaRepresentantelegal">Representante Legal</label>
                        <?php echo $this->Form->input('representantelegal', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre Representante')); ?>
                    </div>   

                    <div class="form-group col-md-3">
                        <label for="EmpresaCiudadeId">Ciudad</label>
                        <?php echo $this->Form->input('ciudade_id', array('label' => '','class' => 'form-control')); ?>
                    </div>   

                    <div class="form-group col-md-3"> 
                        <?php echo $this->Form->input('imagen', array('type' => 'file')); ?>
                        <p class="help-block">Máximo 1MB</p>
                    </div> 
                    
                </div>
                
                

	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Impuestos'), array('controller' => 'impuestos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Impuesto'), array('controller' => 'impuestos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Depósitos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Depósito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Categorias'), array('controller' => 'categorias', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Categoria'), array('controller' => 'categorias', 'action' => 'add')); ?> </li>

	</ul>
</div>
