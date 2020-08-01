<?php echo ($this->Html->script('bandeja/gestionBandejas'));  ?>
<?php echo ($this->Html->script('clientes/clientes.js'));  ?>
<?php $this->layout='inicio'; ?>
<div class="clientes form">
<?php echo $this->Form->create('Cliente', array('class' => 'form-inline')); ?>
    <fieldset>
    <legend><h2><b><?php echo __('Agregar Cliente'); ?></b></h2></legend>
    <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '14', 'id' => 'menuvert'))?>
    <section class="main row">
        
        <div class="col-md-12" style="margin-bottom: 20px;">

            <div class="form-group">
                <label for="ClienteNit">Nit</label>
                <?php echo $this->Form->input('nit', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Nit del Cliente')); ?>
            </div>
            
            <div class="form-group">
                <label for="ClienteTelefono">Teléfono</label>
                <?php echo $this->Form->input('telefono', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Teléfono del Cliente', 'autocomplete' => 'off')); ?>
            </div>

        </div>
        
        <div class="col-md-12" style="margin-bottom: 20px;">

            <div class="form-group">
                <label for="ClienteEmail">E-mail</label>
                <?php echo $this->Form->input('email', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'E-mail del Cliente', 'autocomplete' => 'off')); ?>
            </div>
            
            <div class="form-group">
                <label for="ClienteLimitecredito">Límite Crédito</label>
                <?php echo $this->Form->input('limitecredito', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Límite de Crédito Sugerido', 'autocomplete' => 'off')); ?>
            </div>
            
        </div>        
        
        <div class="col-md-12" style="margin-bottom: 20px;">

            <div class="form-group">
                <label for="ClienteNombre">Nombre</label>
                <?php echo $this->Form->input('nombre', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre del Cliente')); ?>
            </div>
            
            <div class="form-group">
                <label for="ClienteDireccion">Dirección</label>
                <?php echo $this->Form->input('direccion', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Dirección del Cliente')); ?>
            </div>
                        
        </div>
        
        <div class="col-md-12" style="margin-bottom: 20px;">

            <div class="form-group">
                <label for="ClienteCelular">Celular</label>
                <?php echo $this->Form->input('celular', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Celular del Cliente')); ?>
            </div>
            
            <div class="form-group">
                <label for="cumpleanios">Cumpleaños</label><br>
                <input name="data[Cliente][cumpleanios]" class="date form-control" placeholder="Cumpleaños Cliente" type="text" id="cumpleanios">
            </div>            
                                
        </div>
        
        <div class="col-md-12" style="margin-bottom: 20px;">   
            
            <div class="form-group">
                <label for="ClientePaginaweb">Página Web</label>
                <?php echo $this->Form->input('paginaweb', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Página Web del Cliente')); ?>                   
            </div>    
            <div class="form-group">
                <label for="ClienteDiascredito">Días Crédito</label><br>
                <?php echo $this->Form->input('diascredito', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Días de Crédito Sugeridos')); ?>                   
            </div>             
                                
        </div>
        
        
        <div class="col-md-12" style="margin-bottom: 20px;"> 

            <div class="form-group"> 
                <label for="ClienteCiudadeId">Ciudad</label>
                <?php echo $this->Form->input('ciudade_id', array('label' => '', 'class' => 'form-control')); ?>                   
            </div>  
 
            
        </div>
        
        <div class="col-md-12" style="margin-bottom: 20px;">   

            <div class="form-group"> 
                <label for="ClienteDepositoId">Depósito</label>
                <?php echo $this->Form->input('deposito_id', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Nit del Cliente')); ?>                   
            </div>                                         
        </div>  

        <div class="col-md-12" style="margin-bottom: 20px;">   
            
            <div class="form-group">
                <label for="ClienteClasificacionclienteId">Clasificación</label>
                <?php 
                            echo $this->Form->input("clasificacioncliente_id",
                                    array(
                                        'name'=>"data[Cliente][clasificacioncliente_id]",
                                        'label' => "",
                                        'type' => 'select',
                                        'options'=>$clasificacion,
                                        'class' => 'form-control'
                                    )
                            );
                        ?>
            </div>
            
        </div>      
        
        <div class="col-md-12" style="margin-bottom: 20px;"> 

            <div class="form-group"> 
                <label for="ClienteObservaciones">Observaciones</label><br>
                <?php echo $this->Form->input('observaciones', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Observaciones sobre el Cliente', 'autocomplete' => 'off')); ?>                   
            </div>            
        </div>        
        
            <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId)); ?>
            <?php echo $this->Form->input('usuario_id', array('type' => 'hidden', 'value' => $usuarioId)); ?>
            <?php echo $this->Form->input('estado_id', array('type' => 'hidden', 'value' => '1')); ?>   
        
    </section>                            
                
	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Clientes'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Depósitos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Depósito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
	</ul>
</div>
