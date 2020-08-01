<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('vehiculos/vehiculos.js')); ?>
<div class="vehiculos form">
<?php echo $this->Form->create('Vehiculo', array('class' => 'form-inline')); ?>
	<fieldset>
            <legend><h2><b><?php echo __('Editar Vehículo'); ?></b></h2></legend>
            <?php echo $this->Form->input('id', array('type'=> 'hidden', 'value' => $id)); ?>
            <div class="container-fluid"> 
                <div class="col-md-4" style="margin-bottom: 20px;">
                   <label>Tipo Vehículo</label>
                        <?php 
                            echo $this->Form->input("tipovehiculo_id",
                                    array(
                                        'label' => "",
                                        'type' => 'select',
                                        'options'=>$arrTipV,
                                        'empty'=>'Seleccione Uno',
                                        'class' => 'form-control'
                                    )
                            );
                        ?>
                </div>
                
                <div class="col-md-4" style="margin-bottom: 20px;">
                    <div class="form-group">  
                        <label>Placa Vehículo</label><br>  
                            <?php echo $this->Form->input('placa', array('label' => false, 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Placa del Vehículo')); ?>
                    </div>                    
                </div>
                
                <div class="col-md-4" style="margin-bottom: 20px;">
                   <label>Marca Vehículo</label>
                        <?php 
                            echo $this->Form->input("marcavehiculo_id",
                                    array(
                                        'label' => "",
                                        'type' => 'select',
                                        'options'=>$arrMarcas,
                                        'empty'=>'Seleccione Uno',
                                        'class' => 'form-control'
                                    )
                            );
                        ?>
                </div>
            </div>
        
            <div class="container-fluid"> 
                <div class="col-md-4" style="margin-bottom: 20px;">
                    <div class="form-group">  
                        <label>Linea</label><br>  
                            <?php echo $this->Form->input('linea', array('label' => false, 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Linea del Vehículo')); ?>
                    </div>                    
                </div>
                
                <div class="col-md-4" style="margin-bottom: 20px;">
                    <div class="form-group">  
                        <label>Modelo</label><br>  
                            <?php echo $this->Form->input('modelo', array('label' => false, 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Modelo del Vehículo')); ?>
                    </div>                    
                </div>
                
                <div class="col-md-4" style="margin-bottom: 20px;">
                    <div class="form-group">  
                        <label>Color</label><br>  
                            <?php echo $this->Form->input('color', array('label' => false, 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Color del Vehículo')); ?>
                    </div>                    
                </div>
            </div>
        
            <div class="container-fluid"> 
                <div class="col-md-4" style="margin-bottom: 20px;">
                    <div class="form-group">  
                        <label>Cilindraje Vehículo</label><br>  
                            <?php echo $this->Form->input('cilindraje', array('label' => false, 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Cilindraje del Vehículo')); ?>
                    </div>                    
                </div>
                
                <div class="col-md-4" style="margin-bottom: 20px;">
                    <div class="form-group">  
                        <label>Número Motor</label><br>  
                            <?php echo $this->Form->input('num_motor', array('label' => false, 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Número Motor')); ?>
                    </div>                    
                </div>
                
                <div class="col-md-4" style="margin-bottom: 20px;">
                    <div class="form-group">  
                        <label>Número Chasis</label><br>  
                            <?php echo $this->Form->input('num_chasis', array('label' => false, 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Número Chasis')); ?>
                    </div>                    
                </div>
            </div>
            <div class="container-fluid">
                <div class="col-md-4" style="margin-bottom: 20px;">                    
                    <div class="form-group ">  
                        <label>Soat</label><br>
                        <input name="data[Vehiculo][soat]" id="soat" class="date form-control nuevo" placeholder="Fecha vence soat" autocomplete="off" type="text" value='<?php echo $vehiculo['Vehiculo']['soat'];?>'>
                    </div>              
                </div>                                                  
                <div class="col-md-4" style="margin-bottom: 20px;">                    
                    <div class="form-group ">  
                        <label>Tecnomecánico</label><br>
                        <input name="data[Vehiculo][tecno]" id="tecno" class="date form-control nuevo" placeholder="Fecha vence tecnomecanica" autocomplete="off" type="text" value='<?php echo $vehiculo['Vehiculo']['tecno'];?>'>
                    </div>              
                </div>                                                  
                <div class="col-md-4">                    
                    &nbsp;
                </div>                                                  
            </div>            

	</fieldset>
    <br>
    </br>
    <div class="container-fluid">
        <?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
    </div>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Vehículos'), array('action' => 'index')); ?></li>
	</ul>
</div>
