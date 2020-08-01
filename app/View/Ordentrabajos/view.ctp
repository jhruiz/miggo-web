<?php $this->layout='inicio'; ?>
<div class="ordentrabajos form">
	<fieldset>
            <legend><h3><b><?php echo __('General'); ?></b></h3></legend>                        
            
            <div class="container-fluid">
                <div class="row">
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
                                        'class' => 'form-control',
                                        'default'=> !empty($arrOrdenT['0']['OE']['id']) ? $arrOrdenT['0']['OE']['id'] : "",
                                        'disabled' => true
                                    )
                            );
                        ?>
                    </div>
                    <div class="col-md-4">
                        <label>Kilometraje</label>
                        <?php echo $this->Form->input('Kilometraje', 
                                array(
                                    'label' => '',
                                    'class' => 'form-control', 
                                    'placeholder' => 'Kilometraje Actual',
                                    'disabled' => true,
                                    'value' => !empty($arrOrdenT['0']['Ordentrabajo']['kilometraje']) ? $arrOrdenT['0']['Ordentrabajo']['kilometraje'] : ""
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
                                    'placeholder' => 'Placa Vehículo',
                                    'value' => !empty($arrOrdenT['0']['VH']['placa']) ? $arrOrdenT['0']['VH']['placa'] : "",
                                    'disabled' => true
                                    )
                                ); 
                        ?> 
                        <div id="datosVehiculo" style="position:absolute; z-index:1;"></div>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <label>Mecánico</label>
                        <?php 
                            echo $this->Form->input("usuario_id",
                                    array(
                                        'name'=>"usuario",
                                        'label' => "",
                                        'type' => 'select',
                                        'options'=>$arrUsr,
                                        'empty'=>'Seleccione Uno',
                                        'class' => 'form-control',
                                        'default' => !empty($arrOrdenT['0']['US']['id']) ? $arrOrdenT['0']['US']['id'] : "",
                                        'disabled' => true
                                    )
                            );
                        ?>
                    </div>
                    <div class="col-md-4">
                        <label>Cliente</label>
                        <?php echo $this->Form->input('cliente', 
                                array(
                                    'label' => '',
                                    'class' => 'form-control', 
                                    'placeholder' => 'Cliente',
                                    'disabled' => true,
                                    'value' => !empty($arrOrdenT['0']['CL']['id']) ? $arrOrdenT['0']['CL']['nombre'] . " - " . $arrOrdenT['0']['CL']['nit'] : ""
                                    )
                                ); 
                        ?>
                        <div id="datosCliente" style="position:absolute; z-index:1;"></div>
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
                                         'class' => 'form-control',
                                         'disabled' => true,
                                         'default' => !empty($arrOrdenT['0']['PS']['id']) ? $arrOrdenT['0']['PS']['id'] : ""
                                     )
                             );
                         ?>
                    </div>
                </div>
            </div>
            
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <label>Fecha Ingreso</label>
                        <input name="data[Ordentrabajo][fecha_ingreso]" disabled class="date form-control" placeholder="Fecha de Ingreso" type="text" id="fecha_ingreso" value="<?php echo !empty($arrOrdenT['0']['Ordentrabajo']['fecha_ingreso']) ? $arrOrdenT['0']['Ordentrabajo']['fecha_ingreso'] : ''; ?>">                        
                    </div>
                    
                    <div class="col-md-6">
                        <label>Fecha Salida</label>
                        <input name="data[Ordentrabajo][fecha_salida]" disabled class="date form-control" placeholder="Fecha de Salida" type="text" id="fecha_salida" value="<?php echo !empty($arrOrdenT['0']['Ordentrabajo']['fecha_salida']) ? $arrOrdenT['0']['Ordentrabajo']['fecha_salida'] : ''; ?>">                        
                    </div>
   
                </div>
            </div>
            
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <label>Soat</label>
                        <input name="data[Ordentrabajo][soat]" disabled class="date form-control" placeholder="Fecha Vence Soat" type="text" id="fecha_soat" value="<?php echo !empty($arrOrdenT['0']['Ordentrabajo']['soat']) ? $arrOrdenT['0']['Ordentrabajo']['soat'] : "";?>">                        
                    </div>
                    
                    <div class="col-md-6">
                        <label>Tecnomecánica</label>
                        <input name="data[Ordentrabajo][tecnomecanica]" disabled class="date form-control" placeholder="Fecha Vence Tecno" type="text" id="fecha_tecno" value="<?php echo !empty($arrOrdenT['0']['Ordentrabajo']['tecnomecanica']) ? $arrOrdenT['0']['Ordentrabajo']['tecnomecanica'] : "";?>">                        
                    </div>
                </div>
            </div><br>             
            
            <legend><h3><b><?php echo __('Partes del Vehículo'); ?></b></h3></legend>
            
            <div id="partesVehiculo">
                <?php foreach ($arrPartesV as $pv){?>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">&nbsp;</div>
                            <div class="col-md-3">
                                <label><?php echo ($pv['PV']['descripcion']) ?></label><br>
                            </div>
                                                
                            <div class="col-md-3">
                            <?php 
                                echo $this->Form->input("plantaservicio_id",
                                        array(
                                            'name' => "plantaservicio",
                                            'id' => $pv['PV']['id'],
                                            'label' => "",
                                            'type' => 'select',
                                            'options'=>$arrEstadoP,
                                            'empty'=>'Seleccione Uno',
                                            'class' => 'form-control prtVehiculo',
                                            'default' => $pv['OrdentrabajosPartevehiculo']['estadoparte_id'],
                                            'disabled' => true
                                        )
                                );
                            ?>                                                        
                            </div>
                        <div class="col-md-3">&nbsp;</div>
                    </div>
                </div>
                <?php } ?>
            </div>
            
            <br>
            <legend><h3><b><?php echo __('Observaciones'); ?></b></h3></legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <label>Observaciones Mecánico</label>
                        <?php echo $this->Form->input('observaciones_usuario', array(
                            'label' => "",
                            'type' => "textarea",
                            'class' => 'form-control', 
                            'placeholder' => 'Observaciones del mecánico',
                            'disabled' => true,
                            'value' => !empty($arrOrdenT['0']['Ordentrabajo']['observaciones_usuario']) ? $arrOrdenT['0']['Ordentrabajo']['observaciones_usuario'] : ''
                            )); 
                        ?>
                    </div>
                    <div class="col-md-6">
                        <label>Observaciones Cliente</label>
                        <?php echo $this->Form->input('observaciones_cliente', array(
                            'label' => "",
                            'type' => "textarea",
                            'class' => 'form-control', 
                            'placeholder' => 'Observaciones del cliente',
                            'disabled' => true,
                            'value' => !empty($arrOrdenT['0']['Ordentrabajo']['observaciones_cliente']) ? $arrOrdenT['0']['Ordentrabajo']['observaciones_cliente'] : ''
                            )); 
                        ?>
                    </div>
                </div>
            </div><br>
            
            <legend><h3><b><?php echo __('Suministros'); ?></b></h3></legend>
            
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2">&nbsp;</div>
                    <div class="col-md-8">
                        <div class="table-responsive">    
                            <table  id="productosOrden" cellpadding="0" cellspacing="0" class="table">
                                <thead>
                                <tr>                                            
                                    <th><?php echo ('Código'); ?></th>
                                    <th><?php echo ('Nombre'); ?></th>
                                    <th><?php echo ('Cantidad'); ?></th>
                                </tr> 
                                </thead>
                                <tbody class="tProductos">
                                    <?php foreach ($arrSums as $sums) {?>
                                    
                                    <tr id="tr_<?php echo __($sums['CI']['id']);?>">
                                    <td><?php echo __($sums['P']['codigo'])?></td>
                                    <td><?php echo __($sums['P']['descripcion'])?></td>
                                    <td><?php echo __($sums['OrdentrabajosSuministro']['cantidad']);?></td>
                                    </tr>                                 
                                    <?php } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-2">&nbsp;</div>
                </div>                 
            </div>            
            
	</fieldset>
</div>
