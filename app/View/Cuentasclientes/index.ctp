<?php echo ($this->Html->script('cuentasclientes/cuentasclientes.js')); ?>
<?php $this->layout='inicio'; ?>
<div class="cuentasclientes index">   
        <legend><h2><b><?php echo __('Cuentas por Cobrar'); ?></b></h2></legend> 


    <div class="x_panel">

        <div class="x_title">
            <h2><b><?php echo __('Buscar Cuentas'); ?></b></h2>
        </div>
        <?php echo $this->Form->create('Cuentasclientes', array('action' => 'search', 'method' => 'post', 'class' => 'form-inline')); ?>

            <div class="row" style="margin-bottom: 20px">

                <div class="col-md-12">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cliente">Cliente</label><br>
                            <input name="cliente" id="cliente" autocomplete="off" class="form-control" placeholder="Nombre del cliente" type="text">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipopago">Tipo de pago</label><br>
                            <?php echo $this->Form->input('tipopago', array('label' => '', 'name' => 'tipopago', 'empty' => 'Seleccione uno', 'type' => 'select', 'options' => $tipopago, 'class' => 'form-control')); ?>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="consecutivodian">Consecutivo Dian</label><br>
                            <input name="consecutivodian" id="consecutivodian" autocomplete="off" class="form-control" placeholder="Consecutivo Dian" type="text">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="consecutivodv">Consecutivo DV</label><br>
                            <input name="consecutivodv" id="consecutivodv" autocomplete="off" class="form-control" placeholder="Consecutivo DV" type="text">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fechafactura">Fecha factura inicio</label><br>
                            <input name="fechafactura" id="fechafactura" autocomplete="off" class="date form-control" placeholder="Fecha inicio" type="text">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fechafactura_fin">Fecha factura fin</label><br>
                            <input name="fechafactura_fin" id="fechafactura_fin" autocomplete="off" class="date form-control" placeholder="Fecha de fin" type="text">
                        </div>
                    </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-3">
                <div class="form-group ">
                <?php echo $this->Form->submit('Buscar', array('class' => 'btn btn-primary')); ?>
                </div>
            </div>
            <div class="col-md-9">
                &nbsp;
            </div>
        </div>

        </form>
    </div><!-- class="x_panel" -->

	<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '35', 'id' => 'menuvert'))?>    
        <div class="table-responsive">
            <div class="container-fluid">         
                <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                <tr>
                                <th><?php echo $this->Paginator->sort('documento_id'); ?></th>
                                <th><?php echo $this->Paginator->sort('cliente_id'); ?></th>                                                                
                                <th><?php echo $this->Paginator->sort('tipopago_id', 'Tipo Pago'); ?></th>                                                                
                                <th><?php echo $this->Paginator->sort('totalobligacion', 'Total Obligación'); ?></th>
                                <th><?php echo $this->Paginator->sort('factura_id', '# Factura'); ?></th>
                                <th><?php echo $this->Paginator->sort('created', 'Fecha Factura'); ?></th>                                
                                <th><?php echo $this->Paginator->sort('Días Crédito'); ?></th>
                                <th><?php echo $this->Paginator->sort('Fecha Limite'); ?></th>
                                <th><?php echo $this->Paginator->sort('Días Vencido'); ?></th>
                                <th><?php echo $this->Paginator->sort('usuario_id'); ?></th>  
                                <th>&nbsp;</th>
                </tr>
                <?php foreach ($cuentasclientes as $cuentascliente): ?>
                
                <tr class="<?php echo $cuentascliente['Cuentascliente']['color'];?>">
                        <td>
                                <?php echo $this->Html->link($cuentascliente['DC']['codigo'], array('controller' => 'documentos', 'action' => 'view', $cuentascliente['DC']['id'])); ?>
                        </td>
                        <td>
                                <?php echo $this->Html->link($cuentascliente['CL']['nombre'], array('controller' => 'clientes', 'action' => 'view', $cuentascliente['CL']['id'])); ?>
                        </td>                        
                        <td>
                                <?php echo $this->Html->link($cuentascliente['TP']['descripcion'], array('controller' => 'tipopagos', 'action' => 'view', $cuentascliente['TP']['id'])); ?>
                        </td>                        
                        <td class="<?php echo $cuentascliente['Cuentascliente']['limitecredito']; ?>"><?php echo h("$" . number_format($cuentascliente['Cuentascliente']['totalobligacion'],2)); ?>&nbsp;</td>
                        <td>
                            <?php if (!empty($cuentascliente['F']['consecutivodian'])){?>
                                <?php echo $this->Html->link($cuentascliente['F']['prefijo'] . " " . $cuentascliente['F']['consecutivodian'], array('controller' => 'facturas', 'action' => 'view', $cuentascliente['F']['id'])); ?>
                            <?php } else {?>
                                <?php echo $this->Html->link($cuentascliente['F']['prefijo'] . " " . $cuentascliente['F']['consecutivodv'], array('controller' => 'facturas', 'action' => 'view', $cuentascliente['F']['id'])); ?>
                            <?php } ?>
                        </td>
                        <td><?php echo h($cuentascliente['Cuentascliente']['created']); ?>&nbsp;</td>
                        <td><?php echo h(!empty($cuentascliente['Cliente']['diascredito']) ? $cuentascliente['CL']['diascredito'] : "30"); ?>&nbsp;</td>
                        <td><?php echo h($cuentascliente['Cuentascliente']['fechalimitepago']);?>&nbsp;</td>
                        <td><?php echo h($cuentascliente['Cuentascliente']['diasvencido']);?>&nbsp;</td>
                        <td>
                                <?php echo $this->Html->link($cuentascliente['U']['nombre'], array('controller' => 'usuarios', 'action' => 'view', $cuentascliente['U']['id'])); ?>
                        </td>    
                        <td>
                            <button id="pagarCuenta" class="btn btn-primary" onclick="pagarCuenta('<?php echo $cuentascliente['Cuentascliente']['id']?>');">Pagar</button>
                            <button id="eliminarCuenta" class="btn btn-primary" onclick="eliminarCuenta('<?php echo $cuentascliente['Cuentascliente']['id']?>', '<?php echo $cuentascliente['Cuentascliente']['totalobligacion'];?>');">Eliminar</button>
                            <button id="verAbonos" class="btn btn-primary" onclick="verAbonos('<?php echo $cuentascliente['Cuentascliente']['id']?>');">Ver Abonos</button>
                        </td>
                </tr>
                <?php endforeach; ?>
                </table>
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
</div></div><br><br>
<?php echo $this->Form->create('Reporte',array( 'controller' => 'reportes','action'=>'descargarCuentasClientes')); ?>
    <fieldset>    
        <?php echo $this->Form->input('rpcliente', array('type' => 'hidden', 'name' => 'rpcliente', 'value' => $rpcliente)) ?>
        <?php echo $this->Form->input('rptipopago', array('type' => 'hidden', 'name' => 'rptipopago', 'value' => $rptipopago)) ?>
        <?php echo $this->Form->input('rpconsecutivodian', array('type' => 'hidden', 'name' => 'rpconsecutivodian', 'value' => $rpconsecutivodian)) ?>
        <?php echo $this->Form->input('rpconsecutivodv', array('type' => 'hidden', 'name' => 'rpconsecutivodv', 'value' => $rpconsecutivodv)) ?>
        <?php echo $this->Form->input('rpfecha', array('type' => 'hidden', 'name' => 'rpfecha', 'value' => $rpfecha)) ?>
        <?php echo $this->Form->input('rpfechaFin', array('type' => 'hidden', 'name' => 'rpfechaFin', 'value' => $rpfechaFin)) ?>
        <?php echo $this->Form->submit('Descargar',array('class'=>'btn btn-primary')); ?>
    </fieldset>
</form><br><br>
<div id="div_pagarcuenta"></div>