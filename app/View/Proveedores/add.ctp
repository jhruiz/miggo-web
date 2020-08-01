<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('proveedores/proveedores.js')); ?>
<div class="proveedores form">
<?php echo $this->Form->create('Proveedore', array('class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Agregar Proveedor'); ?></b></h2></legend>
                <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '27', 'id' => 'menuvert'))?>
                
                <div class="row">
                    <div class="form-group">
                        <label for="ProveedoreNit">NIT</label>
                        <?php echo $this->Form->input('nit', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nit del Proveedor')); ?>                   
                    </div>
                </div><br>                 
                
                <div class="row"> 
                    <div class="form-group">
                        <label for="ProveedoreNombre">Nombre</label>
                        <?php echo $this->Form->input('nombre', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre del Proveedor')); ?>                   
                    </div>                
                </div><br>          
		
                <div class="row"> 
                    <div class="form-group">
                        <label for="ProveedoreDireccion">Dirección</label>  
                        <?php echo $this->Form->input('direccion', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Dirección del Proveedor')); ?>                   
                    </div>
                </div><br>

                <div class="row"> 
                    <div class="form-group"> 
                        <label for="ProveedoreTelefono">Teléfono</label> 
                        <?php echo $this->Form->input('telefono', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Teléfono del Proveedor')); ?>                   
                    </div>
                </div><br>

                <div class="row"> 
                    <div class="form-group"> 
                        <label for="ProveedoreCiudadeId">Ciudad</label> 
                        <?php echo $this->Form->input('ciudade_id', array('label' => '', 'class' => 'form-control')); ?>                   
                    </div>
                </div><br>

                <div class="row"> 
                    <div class="form-group"> 
                        <label for="ProveedorePaginaweb">Sitio Web</label>
                        <?php echo $this->Form->input('paginaweb', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Página Web')); ?>                   
                    </div>
                </div><br>

                <div class="row"> 
                    <div class="form-group"> 
                        <label for="ProveedoreEmail">E-Mail</label>
                        <?php echo $this->Form->input('email', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'E-Mail Proveedor')); ?>                   
                    </div>
                </div><br>

                <div class="row"> 
                    <div class="form-group"> 
                        <label for="ProveedoreCelular">Celular</label>
                        <?php echo $this->Form->input('celular', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Celular del Proveedor')); ?>                   
                    </div>
                </div><br>

                <div class="row"> 
                    <div class="form-group"> 
                        <label for="ProveedoreDiascredito">Días de Crédito</label>
                        <?php echo $this->Form->input('diascredito', array('label' => '', 'class' => 'form-control number', 'placeholder' => 'Días de Credito')); ?>                   
                    </div>
                </div><br>

                <div class="row"> 
                    <div class="form-group"> 
                        <label for="addonLimiteCredito">Límite de Crédito</label><br>
                        <div id="addonLimiteCredito" class="input-group">
                            <span class="input-group-addon">$</span>  
                            <?php echo $this->Form->input('limitecredito', array('label' => '', 'class' => 'form-control numericPrice', 'placeholder' => 'Límite de Crédito')); ?>                                           
                        </div>
                    </div>
                </div><br>

                <div class="row"> 
                    <div class="form-group"> 
                        <label for="ProveedoreObservaciones">Observaciones</label>
                        <?php echo $this->Form->input('observaciones', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Observaciones para el proveedor')); ?>                   
                    </div>
                </div><br>

                <div class="row"> 
                    <div class="form-group"> 
                        <label for="ProveedoreEstadoId">Estado</label>
                        <?php echo $this->Form->input('estado_id', array('label' => '', 'class' => 'form-control')); ?>                   
                        <?php echo $this->Form->input('usuario_id', array('type' => 'hidden', 'value' => $usuarioId)); ?>
                        <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId)); ?>
                    </div>                
                </div><br>             
	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>  
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>

		<li><?php echo $this->Html->link(__('Lista Proveedores'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Ciudades'), array('controller' => 'ciudades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Ciudade'), array('controller' => 'ciudades', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
