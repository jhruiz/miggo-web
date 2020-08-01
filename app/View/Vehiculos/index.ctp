<?php $this->layout='inicio'; ?>
<div class="vehiculos index">
    
            <?php echo $this->Form->create('Vehiculos',array('action'=>'search','method'=>'post', 'class' => 'form-inline'));?>
            <legend><h2><b><?php echo __('Buscar Vehículo'); ?></b></h2></legend> 
            <div class="row" style="margin-bottom: 20px;">
                <div class="col-md-4">
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
                
                <div class="col-md-4">
                    <div class="form-group">  
                        <label>Placa Vehículo</label><br>  
                            <?php echo $this->Form->input('placa', array('label' => false, 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Placa del Vehículo')); ?>
                    </div>                    
                </div>  
                
                <div class="col-md-4">
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
            
	<legend><h2><b><?php echo __('Vehículos'); ?></b></h2></legend>
        
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                                <th><?php echo ('Tipo Vehículo'); ?></th>
                                <th><?php echo ('Placa'); ?></th>
                                <th><?php echo ('Marca'); ?></th>
                                <th><?php echo ('Linea'); ?></th>
                                <th><?php echo ('Cilindraje'); ?></th>
                                <th><?php echo ('Modelo'); ?></th>
                                <th><?php echo ('Color'); ?></th>
                                <th><?php echo ('Num. Chasis'); ?></th>
                                <th><?php echo ('Num. Motor'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($vehiculos as $vehiculo): ?>
                <tr>
                        <td><?php echo h($arrTipV[$vehiculo['Vehiculo']['tipovehiculo_id']]); ?>&nbsp;</td>
                        <td><?php echo h($vehiculo['Vehiculo']['placa']); ?>&nbsp;</td>
                        <td><?php echo h($arrMarcas[$vehiculo['Vehiculo']['marcavehiculo_id']]); ?>&nbsp;</td>
                        <td><?php echo h($vehiculo['Vehiculo']['linea']); ?>&nbsp;</td>
                        <td><?php echo h($vehiculo['Vehiculo']['cilindraje']); ?>&nbsp;</td>
                        <td><?php echo h($vehiculo['Vehiculo']['modelo']); ?>&nbsp;</td>
                        <td><?php echo h($vehiculo['Vehiculo']['color']); ?>&nbsp;</td>
                        <td><?php echo h($vehiculo['Vehiculo']['num_motor']); ?>&nbsp;</td>
                        <td><?php echo h($vehiculo['Vehiculo']['num_chasis']); ?>&nbsp;</td>
                        <td class="actions">
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Vehículo', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $vehiculo['Vehiculo']['id']))); ?>
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Vehículo', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $vehiculo['Vehiculo']['id']))); ?>                   
                            <?php
                            echo $this->Form->postLink(                        
                              $this->Html->image('png/list-2.png', array('title' => 'Eliminar Vehículo', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                              array('action' => 'delete', $vehiculo['Vehiculo']['id']), //url
                              array('escape' => false), //el escape
                              __('Está seguro que desea eliminar Vehículo?') //la confirmacion
                            ); 
                            ?>                             
                        </td>
                </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>
        <p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Página {:page} de {:pages}, mostrando {:current} registro de {:count} en total, iniciando en registro {:start}, finalizando en {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('Anterior '), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ' || '));
		echo $this->Paginator->next(__(' Siguiente') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Nuevo Vehículo'), array('action' => 'add')); ?></li>
	</ul>
</div>
