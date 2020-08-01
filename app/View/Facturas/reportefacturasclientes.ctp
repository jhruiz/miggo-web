<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('facturas/facturascliente'));?>
<div class="facturasclientes index">  

            <?php echo $this->Form->create('Facturas',array('action'=>'searchFacCli','method'=>'post', 'class' => 'form-inline'));?>
            <legend><h2><b><?php echo __('Buscar Servicios por Cliente'); ?></b></h2></legend>      
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <label>Lista Mecánicos</label>
                        <?php 
                            echo $this->Form->input("usuario_id",
                                    array(
                                        'name'=>"usuario",
                                        'label' => "",
                                        'type' => 'select',
                                        'options'=>$usuarios,
                                        'empty'=>'Seleccione Uno',
                                        'class' => 'form-control'
                                    )
                            );
                        ?>
                    </div>
                    <div class="col-md-3">
                        <label>Fecha Inicial</label><br>
                        <input name="fecha_inicio" class="date form-control" autocomplete="off" placeholder="Fecha Inicio" type="text" id="fecha_inicio">                        
                    </div>                    
                    <div class="col-md-3">
                        <label>Fecha Final</label><br>
                        <input name="fecha_fin" class="date form-control" autocomplete="off" placeholder="Fecha Fin" type="text" id="fecha_fin">                        
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

        <legend><h2><b><?php echo __('Servicios por Cliente'); ?></b></h2></legend>           
        
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0"  class="table table-striped table-bordered table-hover table-condensed">                    
                    <thead>
                        <tr>
                            <th><?php echo ('Cliente'); ?></th>
                            <th><?php echo ('Ident. Cliente'); ?></th>
                            <th><?php echo ('Cel. Cliente'); ?></th>
                            <th><?php echo ('Placa Vehículo'); ?></th>
                            <th><?php echo ('Técnico'); ?></th>
                            <th><?php echo ('Cod. Factura'); ?></th>
                            <th><?php echo ('Fecha Factura'); ?></th>
                            <th><?php echo ('Cant. Facts'); ?></th>
                            <th><?php echo ('Val. Ttal Facts'); ?></th>                            
                        </tr>                        
                    </thead>
                    <tbody>
                        <?php if(!empty($factClientes)){?>
                            <?php foreach ($factClientes as $fc): ?>                            
                            <tr>                           
                                <td><?php echo h($fc['C']['nombre']);?></td>
                                <td><?php echo h($fc['C']['nit']);?></td>
                                <td><?php echo h($fc['C']['celular']);?></td>
                                <td><?php echo h($fc['V']['placa']);?></td>
                                <td><?php echo h($fc['U']['nombre']);?></td>
                                <td><?php echo h($fc['Factura']['codigo']);?></td>
                                <td><?php echo h($fc['Factura']['created']);?></td>
                                <td><?php echo h($fc['0']['conteo']);?></td>
                                <td><?php echo h(number_format($fc['0']['valor'], 2, ',', '.'));?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php } ?>
                    </tbody>
                </table>              
            </div>
        </div>
</div>

