<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('facturas/reporteOrdenesMecanico'));?>
<div class="ordenesmecanicos index">
    
            <?php echo $this->Form->create('Facturas',array('action'=>'searchOrdMec','method'=>'post', 'class' => 'form-inline'));?>
            <legend><h2><b><?php echo __('Buscar Ordenes - Mecánicos'); ?></b></h2></legend>      
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <label>Lista Mecánicos</label>
                        <?php 
                            echo $this->Form->input("usuario_id",
                                    array(
                                        'name'=>"usuario",
                                        'label' => "",
                                        'type' => 'select',
                                        'options'=>$listMecanicos,
                                        'empty'=>'Seleccione Uno',
                                        'class' => 'form-control input-sm',
                                        'style' => 'width:250px'
                                    )
                            );
                        ?>
                    </div>
                    <div class="col-md-6">
                        <label>Estado del pago</label>
                        <?php 
                            echo $this->Form->input("estadopagomecanico_id",
                                    array(
                                        'name'=>"estadopagomecanico",
                                        'label' => "",
                                        'type' => 'select',
                                        'options'=>$estadoPago,
                                        'empty'=>'Seleccione Uno',
                                        'class' => 'form-control',
                                        'style' => 'width:250px'
                                    )
                            );
                        ?>
                    </div>
                </div>
                <div class="row" style="margin-top:15px;">
                    <div class="col-md-6">
                        <label>Fecha Inicial</label><br>
                        <input name="fecha_inicio" class="date form-control" autocomplete="off" placeholder="Fecha Inicio" type="text" id="fecha_inicio" style='width:250px'>                        
                    </div>                    
                    <div class="col-md-6">
                        <label>Fecha Final</label><br>
                        <input name="fecha_fin" class="date form-control" autocomplete="off" placeholder="Fecha Fin" type="text" id="fecha_fin" style='width:250px'>
                    </div>                    
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
        </div><br>

        </form>             
            
            <legend><h2><b><?php echo __('Servicios por Mecánico'); ?></b></h2></legend>
        
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0"  class="table table-striped table-bordered table-hover table-condensed">                    
                    <thead>
                        <tr>
                            <th><?php echo ('Orden'); ?></th>
                            <th><?php echo ('Factura'); ?></th>
                            <th><?php echo ('Fecha Factura'); ?></th>
                            <th><?php echo ('Mecánico'); ?></th>
                            <th><?php echo ('Servicio'); ?></th>
                            <th><?php echo ('Placa'); ?></th>
                            <th><?php echo ('cantidad'); ?></th>
                            <th><?php echo ('Costo'); ?></th>
                            <th><?php echo ('Total'); ?></th>
                            <th><?php echo ('Pago?'); ?></th>
                            <th><?php echo ('Fecha de pago'); ?></th>
                        </tr>                        
                    </thead>
                    <tbody>
                        <?php if(!empty($arrFactOrdenes)){?>
                            <?php foreach ($arrFactOrdenes as $FO): ?>
                            <?php $estadopago = !empty($FO['Factura']['estadopagomecanico_id']) && $FO['Factura']['estadopagomecanico_id'] != '1' ? 'checked disabled' : ''; ?>
                            <tr>                           
                                <td>
                                    <?php echo $this->Html->link($FO['OT']['codigo'], '/ordentrabajos/view/' . $FO['OT']['id'], array('target' => '_blank')); ?>
                                </td>
                                <td><?php echo $this->Html->link($FO['Factura']['codigo'],'/facturas/view/' . $FO['Factura']['id'],array('target' => '_blank')); ?></td>
                                <td><?php echo h($FO['Factura']['created']); ?>&nbsp;</td>
                                <td><?php echo h($FO['US']['nombre'] . " - " . $FO['US']['identificacion']); ?>&nbsp;</td>
                                <td><?php echo h($FO['PR']['descripcion']); ?>&nbsp;</td>
                                <td><?php echo h($FO['VH']['placa']); ?>&nbsp;</td>
                                <td class="text-right"><?php echo (number_format($FO['FD']['cantidad'], '0', '.', ',')); ?>&nbsp;</td>
                                <td class="text-right">$ <?php echo (number_format($FO['FD']['costoventa'], '0', '.', ',')); ?>&nbsp;</td>
                                <td class="text-right">$ <?php echo (number_format($FO['FD']['costototal'], '0', '.', ',')); ?>&nbsp;</td>
                                
                                <td><input type="checkbox" <?php echo $estadopago ?> id="<?php echo $FO['Factura']['id']; ?>" class="estadopago"</td>
                                
                                <td id="fecpag_<?php echo $FO['Factura']['id'];?>"><?php echo $FO['Factura']['fechapagoservicio']; ?></td>
                            </tr>

                            <?php $totServ += $FO['FD']['cantidad']; ?>
                            <?php $subTot += $FO['FD']['costoventa']; ?>
                            <?php $total += $FO['FD']['costototal']; ?>

                            <?php endforeach; ?>
                        <?php } ?>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                            <td><b>TOTAL</b></td>
                            <td class="text-right"><b><?php echo (number_format($totServ, '0', '.', ',')); ?></b></td>
                            <td class="text-right"><b>$ <?php echo (number_format($subTot, '0', '.', ',')); ?></b></td>
                            <td class="text-right"><b>$ <?php echo (number_format($total, '0', '.', ',')); ?></b></td>
                        </tr>
                    </tbody>
                </table>              
            </div>
        </div>
</div>

