<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('cuentaspendientes/cuentaspendientes.js'));?>
<div class="cuentaspendientes index">
    
            <?php echo $this->Form->create('Cuentaspendientes',array('action'=>'search','method'=>'post', 'class' => 'form-inline'));?>
            <legend><h2><b><?php echo __('Buscar Cuentas por Pagar'); ?></b></h2></legend>  
            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '29', 'id' => 'menuvert'))?>    
            <div class="row">                                
                
                <div class="col-md-12" style="margin-bottom: 20px;">
                    
                    <div class="col-md-6">
                        <div class="form-group">  
                            <label for="CuentaspendientesProducto">Producto</label>                         
                            <?php echo $this->Form->input('producto', array('label' => '', 'style' => 'width:50%', 'name' => 'productos', 'empty' => 'Seleccione uno', 'options' => $productos, 'class' => 'form-control'));?>
                        </div>                
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">  
                            <label for="CuentaspendientesDeposito">Depósito</label>                        
                            <?php echo $this->Form->input('deposito', array('label' => '', 'name' => 'depositos', 'empty' => 'Seleccione uno', 'type' => 'select', 'options' => $depositos, 'class' => 'form-control'));?>
                        </div>             
                    </div>
                </div>
                
                <div class="col-md-12" style="margin-bottom: 20px;">

                    <div class="col-md-6">
                        <div class="form-group">  
                            <label for="CuentaspendientesProveedor">Proveedor</label>                       
                            <?php echo $this->Form->input('proveedor', array('label' => '', 'name' => 'proveedores', 'empty' => 'Seleccione uno', 'type' => 'select', 'options' => $proveedores, 'class' => 'form-control'));?>
                        </div>             
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">  
                            <label for="factura">#Factura</label><br>              
                            <input name="facturas" id="factura" class="form-control" placeholder="Número de Factura" type="text">
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
    
	<legend><h2><b><?php echo __('Cuentas por Pagar'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container-fluid">                                       
                <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                <tr>
                                <th><?php echo $this->Paginator->sort('documento_id'); ?></th>
                                <th><?php echo $this->Paginator->sort('producto_id'); ?></th>
                                <th><?php echo $this->Paginator->sort('Costo Producto'); ?></th>
                                <th><?php echo $this->Paginator->sort('Cantidad'); ?></th>
                                <th><?php echo $this->Paginator->sort('Costo Total'); ?></th>
                                <th><?php echo $this->Paginator->sort('proveedore_id', 'Proveedor'); ?></th>
                                <th><?php echo $this->Paginator->sort('# Factura'); ?></th>
                                <th><?php echo $this->Paginator->sort('created', 'Fecha Factura'); ?></th>
                                <th><?php echo $this->Paginator->sort('Días Crédito'); ?></th>
                                <th><?php echo $this->Paginator->sort('Fecha Limite'); ?></th>
                                <th><?php echo $this->Paginator->sort('Días Vencido'); ?></th>
                                <th>&nbsp;</th>
                </tr>
                <?php foreach ($cuentaspendientes as $cuentaspendiente): ?>                
                <tr class="<?php echo $cuentaspendiente['Cuentaspendiente']['color'];?>">
                        <td><?php echo $this->Html->link($cuentaspendiente['Documento']['codigo'], array('controller' => 'documentos', 'action' => 'view', $cuentaspendiente['Documento']['id'])); ?></td>
                        <td><?php echo $this->Html->link($cuentaspendiente['Producto']['descripcion'], array('controller' => 'productos', 'action' => 'view', $cuentaspendiente['Producto']['id'])); ?></td>
                        <td><?php echo h("$" . number_format($cuentaspendiente['Cuentaspendiente']['costoproducto'],2)); ?>&nbsp;</td>
                        <td><?php echo h($cuentaspendiente['Cuentaspendiente']['cantidad']); ?>&nbsp;</td>
                        <td class="<?php echo $cuentaspendiente['Cuentaspendiente']['limitecredito']; ?>"><?php echo h("$" . number_format($cuentaspendiente['Cuentaspendiente']['totalobligacion'],2)); ?>&nbsp;</td>
                        <td><?php echo $this->Html->link($cuentaspendiente['Proveedore']['nombre'], array('controller' => 'proveedores', 'action' => 'view', $cuentaspendiente['Proveedore']['id'])); ?></td>
                        <td><?php echo h($cuentaspendiente['Cuentaspendiente']['numerofactura']); ?>&nbsp;</td>
                        <td><?php echo h($cuentaspendiente['Cuentaspendiente']['created']); ?>&nbsp;</td>
                        <td><?php echo h($cuentaspendiente['Proveedore']['diascredito']); ?>&nbsp;</td>
                        <td><?php echo h($cuentaspendiente['Cuentaspendiente']['fechalimitepago']);?>&nbsp;</td>
                        <td><?php echo h($cuentaspendiente['Cuentaspendiente']['diasvencido']);?>&nbsp;</td>
                        <td><button id="pagarCuenta" class="btn btn-primary btn-sm" onclick="datosCuentasPendientes('<?php echo $cuentaspendiente['Cuentaspendiente']['id']?>');">Pagar</button>
                        <button id="eliminarCuentaPendiente" class="btn btn-primary btn-sm" onclick="eliminarCuentaPendiente('<?php echo $cuentaspendiente['Cuentaspendiente']['id']?>');">Eliminar</button>
                        </td>
                </tr>
                <?php endforeach; ?>
                </table>             
            </div>
        </div>
        <legend>&nbsp;</legend>
                
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                &nbsp;
            </div>              
            <div class="col-md-2">
                <dl>                    
                    <dt class="text-left text-success"><?php echo h("Saldo Vigente: ");?></dt>
                    <dt class="text-left text-danger"><?php echo h("Saldo Vencido: ");?></dt>
                    <dt class="text-left text-info"><?php echo h("Saldo Total: ");?></dt>
                </dl>
            </div>         
            <div class="col-md-2">                
                <dl>
                    <dt class="text-right text-success"><?php echo h("$" . number_format($costoVigente,2))?></dt>
                    <dt class="text-right text-danger"><?php echo h("$" . number_format($costoVencido,2))?></dt>
                    <dt class="text-right text-info"><?php echo h("$" . number_format($costoTotal,2))?></dt>
                </dl>
            </div>        
        </div>
    </div>   
    <?php echo $this->Form->create('Reporte',array( 'controller' => 'reportes','action'=>'descargarCuentasPendientes')); ?>
        <fieldset>
            <?php echo $this->Form->input('rpproducto', array('type' => 'hidden', 'name' => 'rpproducto', 'value' => $rpproducto))?>
            <?php echo $this->Form->input('rpdeposito', array('type' => 'hidden', 'name' => 'rpdeposito', 'value' => $rpdeposito))?>
            <?php echo $this->Form->input('rpproveedor', array('type' => 'hidden', 'name' => 'rpproveedor', 'value' => $rpproveedor))?>
            <?php echo $this->Form->input('rpfactura', array('type' => 'hidden', 'name' => 'rpfactura', 'value' => $rpfactura))?>
            <?php echo $this->Form->submit('Descargar',array('class'=>'btn btn-primary')); ?>
        </fieldset>
    </form>        
    <div id="div_pagarcuenta"></div>
