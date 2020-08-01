<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('bandeja/gestionBandejas.js'));?>
<div class="facturas-cuentas-valores index">
    
    <?php echo $this->Form->create('FacturaCuentaValore',array('action'=>'search','method'=>'post', 'class' => 'form-inline'));?>
    <legend><h2><b><?php echo __('Bucar de pagos por factura'); ?></b></h2></legend> 
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">  
                <label>Fecha Inicial</label><br>
                <input name="data[fechaInicio]" id="fechaInicio" autocomplete="off" class="date form-control" placeholder="Fecha Inicio" type="text">                             
            </div>             
        </div>       
        <div class="col-md-3">
            <div class="form-group">
                <label>Fecha Final</label><br>                          
                <input name="data[fechaFin]" id="fechaFin" autocomplete="off" class="date form-control" placeholder="Fecha Fin" type="text">               
            </div>        
        </div>

        <div class="col-md-3">
                <label>Cuentas</label>
                <?php 
                    echo $this->Form->input("tipocuentas",
                            array(
                                'name'=>"tipocuentas",
                                'label' => "",
                                'type' => 'select',
                                'options'=>$tipoCuentas,
                                'empty'=>'Seleccione Uno',
                                'class' => 'form-control'
                            )
                    );
                ?>
        </div>                      
        <div class="col-md-3">
                <label>Tipos de Pago</label>
                <?php 
                    echo $this->Form->input("tipopagos",
                            array(
                                'name'=>"tipopagos",
                                'label' => "",
                                'type' => 'select',
                                'options'=>$tipoPago,
                                'empty'=>'Seleccione Uno',
                                'class' => 'form-control'
                            )
                    );
                ?>
        </div>                      
    </div><br>              
    <?php echo $this->Form->submit('Buscar',array('class'=>'btn btn-primary'));?>
    </form><br><br>  
    
    
	<legend><h2><b><?php echo __('Métodos de Pago por Factura'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                                <th><?php echo $this->Paginator->sort('factura_id'); ?></th>                                
                                <th><?php echo $this->Paginator->sort('created', 'Fecha'); ?></th>                                
                                <th><?php echo $this->Paginator->sort('valorFactura', 'Valor Factura'); ?></th>
                                <th><?php echo $this->Paginator->sort('cuenta_id'); ?></th>                                
                                <th><?php echo $this->Paginator->sort('tipopago_id'); ?></th>
                                <th><?php echo $this->Paginator->sort('valor'); ?></th>
                </tr>
                <?php foreach ($pagosFacturas as $pagofact): ?>
                    <?php if(!empty($pagofact['F']['id'])) { ?>
                        <tr>
                            <td><?php echo !empty($pagofact['F']['consecutivodian']) ? h($pagofact['F']['consecutivodian']) : $pagofact['F']['codigo']; ?>&nbsp;</td>
                            <td><?php echo h($pagofact['F']['created']); ?>&nbsp;</td>
                            <td><?php echo h("$" . number_format($pagofact['F']['pagocontado'], 2)); ?>&nbsp;</td>                            
                            <td><?php echo h($pagofact['C']['descripcion']); ?>&nbsp;</td>                            
                            <td><?php echo h($pagofact['T']['descripcion']); ?>&nbsp;</td>
                            <td><?php echo h("$" . number_format($pagofact['FacturaCuentaValore']['valor'], 2)); ?>&nbsp;</td>
                        </tr>
                    <?php }?>
                <?php endforeach; ?>
                </table>                
            </div>
        </div>
</div>
