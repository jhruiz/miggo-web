<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('ordentrabajos/indexOrdenTrabajo'));?>
<div class="ordentrabajos index">
            <?php echo $this->Form->create('Ordentrabajos',array('action'=>'search','method'=>'post'));?>
            <legend><h2><b><?php echo __('Buscar Ordenes de Trabajo'); ?></b></h2></legend>   
            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '41', 'id' => 'menuvert'))?> 
            <?php echo $this->Form->input('vehiculo_id', array('type' => 'hidden', 'id' => 'vehiculo_id', 'name' => 'vehiculo'))?> 
            <div class="container-fluid" style="margin-bottom: 20px;">      
                <div class="col-md-4">
                   <label>Usuarios</label>
                        <?php 
                            echo $this->Form->input("usuario_id",
                                    array(
                                        'name'=>"usuario",
                                        'label' => "",
                                        'type' => 'select',
                                        'options'=>$arrUsr,
                                        'empty'=>'Seleccione Uno',
                                        'class' => 'form-control'
                                    )
                            );
                        ?>           
                </div>       
                
                <div class="col-md-4">
                   <label>Clientes</label>
                        <?php 
                            echo $this->Form->input("cliente_id",
                                    array(
                                        'name'=>"cliente",
                                        'label' => "",
                                        'type' => 'select',
                                        'options'=>$arrClientes,
                                        'empty'=>'Seleccione Uno',
                                        'class' => 'form-control'
                                    )
                            );
                        ?>
                </div>
                
                <div class="col-md-4">
                   <label>Planta</label>
                        <?php 
                            echo $this->Form->input("plantaservicio_id",
                                    array(
                                        'name'=>"plantaservicio",
                                        'label' => "",
                                        'type' => 'select',
                                        'options'=>$arrPlantas,
                                        'empty'=>'Seleccione Uno',
                                        'class' => 'form-control'
                                    )
                            );
                        ?>            
                </div>                              
            </div>
            
            <div class="container-fluid"  style="margin-bottom: 20px;">
                
                <div class="col-md-4">
                   <label>Estado</label>
                        <?php 
                            echo $this->Form->input("ordenestado_id",
                                    array(
                                        'name'=>"ordenestado",
                                        'label' => "",
                                        'type' => 'select',
                                        'options'=>$arrOrdenEst,
                                        'empty'=>'Seleccione Uno',
                                        'class' => 'form-control'
                                    )
                            );
                        ?>          
                </div>
                
                <div class="col-md-4">
                    <label>Placa</label>
                    <?php echo $this->Form->input('placa', 
                            array(
                                'label' => '',
                                'class' => 'form-control', 
                                'placeholder' => 'Placa Vehículo'
                                )
                            ); 
                    ?> 
                    <div id="datosVehiculo" style="position:absolute; z-index:1;"></div>
                </div>

                <div class="col-md-4">&nbsp;</div>
            </div>    
            <br><br>       
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
            
	<legend><h2><b><?php echo __('Ordenes de Trabajo'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">
                <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                    <tr>
                        
                        <?php if(isset($arrOrdenT['0']['semaforo'])){?><th>&nbsp;</th> <?php }?>
                        <th><?php echo ('Código'); ?></th>
                        <th><?php echo ('Mecánico'); ?></th>
                        <th><?php echo ('Cliente'); ?></th>
                        <th><?php echo ('Vehículo'); ?></th>
                        <th><?php echo ('Estado'); ?></th>
                        <th class="actions"><?php echo __('Acciones'); ?></th>
                    </tr>
                    <?php foreach ($arrOrdenT as $ot): ?>
                    <tr>
                        
                            <?php if(isset($ot['semaforo']['color'])){?>
                            <td>
                                <center>
                                    <div style="border-width: 4px; border-radius: 25px; width: 35px;  background: #<?php 
                                            echo $ot['semaforo']['color'];                                       
                                    ?>; "><?php echo $ot['semaforo']['dias']; ?>
                                    </div>
                                </center>
                            </td>             
                            <?php } ?>
                            <td><?php echo h($ot['Ordentrabajo']['codigo']); ?>&nbsp;</td>
                            <td><?php echo h($ot['US']['nombre']); ?>&nbsp;</td>
                            <td><?php echo h(!empty($ot['CL']['nombre']) ? $ot['CL']['nombre'] : ""); ?>&nbsp;</td>
                            <td><?php echo h(!empty($ot['VH']['placa']) ? $ot['VH']['placa'] : ""); ?>&nbsp;</td>
                            <td><?php echo h(!empty($ot['OE']['descripcion']) ? $ot['OE']['descripcion'] : ""); ?>&nbsp;</td>
                            <td class="actions">
                                <?php if(array_key_exists($ot['OE']['id'], $estFin) && !$flgE){?>
                                <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Orden Trabajo', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $ot['Ordentrabajo']['id']))); ?>                                    
                                <?php }else{ ?>
                                <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Orden Trabajo', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $ot['Ordentrabajo']['id']))); ?>
                                <?php } ?>
                                <?php if(!array_key_exists($ot['OE']['id'],$estFin) && $flgE){?>                                
                                    <?php
                                    echo $this->Form->postLink(                        
                                      $this->Html->image('png/list-2.png', array('title' => 'Eliminar Orden Trabajo', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                                      array('action' => 'delete', $ot['Ordentrabajo']['id']), //url
                                      array('escape' => false), //el escape
                                      __('Está seguro que desea eliminar la orden de trabajo?') //la confirmacion
                                    ); 
                                    ?>                                 
                                <?php } ?>
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
	<?php echo $this->Paginator->prev('< ' . __('Anterior '), array(), null, array('class' => 'prev disabled')); ?>
	<?php echo $this->Paginator->numbers(array('separator' => ' || ')); ?>
	<?php echo $this->Paginator->next(__(' Siguiente') . ' >', array(), null, array('class' => 'next disabled')); ?>

	</div>        
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Nueva Orden de Trabajo'), array('action' => 'add')); ?></li>
	</ul>
</div>
