<?php $this->layout='inicio'; ?>
<div class="container body">
<div class="main_container">
    <div class="prefacturas index">
   
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?php echo __('Buscar Orden de Pedido'); ?></h2>
                </div>
                <div class="x_content">
                    <?php echo $this->Form->create('Prefactura',array('action'=>'search','method'=>'post'));?>      
                    <div class="row">

                    <div class="col-md-3">
                        <div class="form-group ">  
                            <label>Cliente</label><br>                          
                            <input name="cliente" class="form-control" placeholder="Nombre del Cliente" type="text">
                        </div>             
                    </div>       
                
                    <div class="col-md-3">
                        <div class="form-group ">  
                            <label>Vehículo</label><br>                          
                            <input name="vehiculo" class="form-control" placeholder="Placa del Vehículo" type="text">
                        </div>             
                    </div>
                
                    <div class="col-md-6">&nbsp;</div>
                                          
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

            </form>
            <div class="x_content">
            </div> 
        <!--termina col-->
        </div>
    </div>
        
        
        
    <div class="row">
        <div class="col-md-12">
             <div class="x_panel">
                <div class="x_title">
                    <h2><?php echo __('Orden de Pedido'); ?></h2>
                </div>
                <div class="x_content">
	                <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '30', 'id' => 'menuvert'))?>
                        <div class="">
                            <div class="container">
                                <table class="table table-hover" cellpadding="0" cellspacing="0" >
                                    <thead>
                                        <tr>
                                            <th><?php echo ('Cliente'); ?></th>
                                            <th><?php echo ('Vehículo'); ?></th>
                                            <th><?php echo ('Fecha'); ?></th>
                                            <th><?php echo ('Estado'); ?></th>
                                            <th class="actions"><?php echo __('Acciones'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($prefacturas as $prefactura): ?>
                                        <tr>
                                            <td><?php echo h($prefactura['CL']['nombre']); ?>&nbsp;</td>
                                            <td><?php echo h($prefactura['VH']['placa']); ?>&nbsp;</td>
                                            <td><?php echo h($prefactura['Prefactura']['created']); ?>&nbsp;</td>
                                            <td><?php echo h(!empty($prefactura['Prefactura']['estadoprefactura_id'])  
                                                    ? $estados[$prefactura['Prefactura']['estadoprefactura_id']] : ""); ?>&nbsp;</td>
                                            <td class="actions">
                                                <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Orden de Compra', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $prefactura['Prefactura']['id']))); ?>
                                                <?php
                                                echo $this->Form->postLink(                        
                                                $this->Html->image('png/list-2.png', array('title' => 'Eliminar Orden de Compra', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                                                array('action' => 'delete', $prefactura['Prefactura']['id']), //url
                                                array('escape' => false), //el escape
                                                __('Está seguro que desea eliminar la orden de Compra?') //la confirmacion
                                                ); 
                                                ?> 
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <b>VALOR PREFACTURAS</b>
                                            </td>
                                            <td>
                                                <b><?php echo "$" . number_format($prefactValor); ?></b>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!--Termina content-->
                </div><!-- Termina panel x-->
            </div><!--Termina col-->
        </div>
    </div>
</div>
</div>