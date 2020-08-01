<?php $this->layout='inicio'; ?>
<div class="partevehiculostipovehiculos index">
    
            <?php echo $this->Form->create('PartevehiculosTipovehiculos',array('action'=>'search','method'=>'post'));?>
            <legend><h2><b><?php echo __('Buscar Tipo Vehículo - Partes Vehículo'); ?></b></h2></legend>      
            <div class="form-group"> 
                <div class="col-md-6">
                   <label>Tipo Vehículo</label>
                    <div class="input-group">
                        <?php 
                            echo $this->Form->input("tipovehiculo_id",
                                    array(
                                        'name'=>"tipovehiculo",
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
                                            'name'=>"partevehiculo",
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
          
        <div class="row">
            <div class="col-md-3">
                <div class="form-group ">  
                <?php echo $this->Form->submit('Buscar',array('class'=>'btn btn-primary'));?>                
                </div>             
            </div>
            <div class="col-md-9">
                &nbsp;
            </div>
        </div>            

        </form><br><br>              
            
	<legend><h2><b><?php echo __('Tipo Vehículos - Partes Vehículos'); ?></b></h2></legend>
        
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                                <th><?php echo ('Tipo Vehículo'); ?></th>
                                <th><?php echo ('Parte Vehículo'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($arrTipParVehiculo as $tipPartV): ?>
                <tr>
                        <td><?php echo h($tipPartV['TV']['descripcion']); ?>&nbsp;</td>
                        <td><?php echo h($tipPartV['PV']['descripcion']); ?>&nbsp;</td>
                        <td class="actions">
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Tipo Vehículo - Parte Vehículo', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $tipPartV['PartevehiculosTipovehiculo']['id']))); ?>
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Tipo Vehículo - Parte Vehículo', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $tipPartV['PartevehiculosTipovehiculo']['id']))); ?>                   
                            <?php
                            echo $this->Form->postLink(                        
                              $this->Html->image('png/list-2.png', array('title' => 'Eliminar Parte Vehículo', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                              array('action' => 'delete', $tipPartV['PartevehiculosTipovehiculo']['id']), //url
                              array('escape' => false), //el escape
                              __('Está seguro que desea eliminar el Tipo Vehículo - Parte del Vehículo?') //la confirmacion
                            ); 
                            ?>                             
                        </td>
                </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>

</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Nuevo Tipo Vehículo - Parte Vehículo'), array('action' => 'add')); ?></li>
	</ul>
</div>
