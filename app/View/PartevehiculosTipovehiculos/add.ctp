<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('partevehiculostipovehiculos/addpartevehiculostipovehiculos.js'));  ?>
<div class="partevehiculos form">
<?php echo $this->Form->create('Partevehiculo'); ?>
	<fieldset>
	<legend><h2><b><?php echo __('Agregar Parte de Vehículo'); ?></b></h2></legend>
            <div class="form-group"> 
                <div class="col-md-6">
                   <label>Tipo Vehículo</label>
                    <div class="input-group">
                        <?php 
                            echo $this->Form->input("tipovehiculo_id",
                                    array(
                                        'name'=>"tipovehiculo_id",
                                        'label' => "",
                                        'type' => 'select',
                                        'options'=>$arrTipoVehiculo,
                                        'empty'=>'Seleccione Uno',
                                        'class' => 'form-control'
                                    )
                            );
                        ?>
                    </div>                    
                </div>
                <div class="col-md-6">
                    <div class="form-group"> 
                        <label>Parte Vehículo</label>
                        <div class="input-group">
                            <?php 
                                echo $this->Form->input("partevehiculo_id",
                                        array(
                                            'name'=>"partevehiculo_id",
                                            'label' => "",
                                            'type' => 'select',
                                            'options'=>$arrParteVehiculo,
                                            'empty'=>'Seleccione Uno',
                                            'class' => 'form-control'
                                        )
                                );
                            ?>
                        </div>
                    </div>                      
                </div>
            </div>

	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Partes de Vehículo'), array('action' => 'index')); ?></li>
	</ul>
</div>
