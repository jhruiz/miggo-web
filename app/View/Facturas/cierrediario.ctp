<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('bandeja/gestionBandejas.js'));?>
<?php echo ($this->Html->script('bandeja/observacioncierre.js'));?>

<div class="index">
    
    <?php echo $this->Form->create('Facturas',array('action'=>'buscarcierre','method'=>'post', 'autocomplete' => 'off', 'class' => 'form-inline'));?>
    <legend><h2><b><?php echo __('Buscar Fecha de Cierre'); ?></b></h2></legend> 
    <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '32', 'id' => 'menuvert'))?>  
    
    <div class="form-group">
        <label for="CierrediarioFecha">Fecha: </label><br>
        <input name="data[Cierrediario][Fecha]" id="CierrediarioFecha" class="date form-control" placeholder="Fecha de Cierre" type="text" autocomplete="off">
    </div>
    <div class="form-group">
        <label for="FacturasCuenta">Cuentas: </label>
        <?php echo $this->Form->input('cuenta', array('label' => '', 'name' => 'data[Cierrediario][Cuenta]', 'empty' => 'Seleccione una', 'type' => 'select', 'options' => $listCuenta, 'class' => 'form-control'));?>
    </div><br><br>                          
    <?php echo $this->Form->submit('Buscar',array('class'=>'btn btn-primary'));?>
    </form><br><br>    
    
    
	<legend><h2><b><?php echo __('Detalle Cierre Diario: ' . $fechaCierre); ?></b></h2></legend>        
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                <tr>
                                <th><?php echo ('# Factura'); ?></th>
                                <th><?php echo ('Cliente'); ?></th>
                                <th><?php echo ('Vendedor'); ?></th>
                                <th><?php echo ('Cuenta'); ?></th>                                
                                <th><?php echo ('Tipo Pago'); ?></th>                                
                                <th class="text-right"><?php echo ('Valor'); ?></th>
                </tr>
                <?php foreach ($ventasFactura as $dFact): ?>
                <tr>
                    <td><?php echo(!empty($dFact['consecutivodian']) ? $dFact['consecutivodian'] : $dFact['fact_codigo']);?></td>
                    <td><?php echo(!empty($dFact['cliente_nombre']) ? $dFact['cliente_nombre'] . " - " . $dFact['cliente_nit'] : 'Venta An¨®nima');?></td>
                    <td><?php echo h($dFact['usuario_nombre'] . " - " . $dFact['usuario_identificacion']); ?>&nbsp;</td>
                    <td><?php echo h($listCuenta[$dFact['fcv_cuenta']]); ?>&nbsp;</td>
                    <td><?php echo h($listTipoPago[$dFact['fcv_tipopago']]); ?>&nbsp;</td>
                    <td align="right"><?php echo h('$' . number_format($dFact['fcv_valor'],2)); ?>&nbsp;</td>
                </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>
        
        
	<legend><h2><b><?php echo __('Detalle Gastos: ' . $fechaCierre); ?></b></h2></legend>        
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                <tr>
                    <th><?php echo ('Descripcion'); ?></th>
                    <th><?php echo ('usuario'); ?></th>
                    <th><?php echo ('Fecha del Gasto'); ?></th>                    
                    <th><?php echo ('Cuenta'); ?></th>
                    <th><?php echo ('Tipo'); ?></th>
                    <th class="text-right"><?php echo ('Valor'); ?></th>
                </tr>
                <?php foreach ($infoGastos as $gt): ?>
                <tr>
                    <td><?php echo h($gt['Gasto']['descripcion']); ?>&nbsp;</td>
                    <td><?php echo h($gt['Usuario']['nombre']); ?>&nbsp;</td>
                    <td><?php echo h($gt['Gasto']['fechagasto']); ?>&nbsp;</td>                    
                    <td><?php echo h($gt['Cuenta']['descripcion']); ?>&nbsp;</td>
                    <td><?php echo h(!empty($gt['Cuenta']['traslado']) ? "Traslado" : "Gasto"); ?>&nbsp;</td>
                    <td align="right"><?php echo h('$' . number_format($gt['Gasto']['valor'],2)); ?>&nbsp;</td>                    
                </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>
        
        
	<legend><h2><b><?php echo __('Detalle Traslados: ' . $fechaCierre); ?></b></h2></legend>        
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                <tr>
                    <th><?php echo ('Descripcion'); ?></th>
                    <th><?php echo ('usuario'); ?></th>
                    <th><?php echo ('Fecha del Traslado'); ?></th>                    
                    <th><?php echo ('Cuenta Origen'); ?></th>
                    <th><?php echo ('Cuenta Destino'); ?></th>
                    <th class="text-right"><?php echo ('Valor'); ?></th>
                </tr>
                <?php foreach ($infoTraslados as $trs): ?> 
                <tr>
                    <td><?php echo h($trs['Gasto']['descripcion']); ?>&nbsp;</td>
                    <td><?php echo h($trs['Usuario']['nombre']); ?>&nbsp;</td>
                    <td><?php echo h($trs['Gasto']['fechagasto']); ?>&nbsp;</td>                    
                    <td><?php echo h($trs['Cuenta']['descripcion']); ?>&nbsp;</td>
                    <td><?php echo h($listCuenta[$trs['Gasto']['cuentadestino']]); ?>&nbsp;</td>
                    <td align="right"><?php echo h('$' . number_format($trs['Gasto']['valor'],2)); ?>&nbsp;</td>                    
                </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div> 
        
	<legend><h2><b><?php echo __('Detalle Abonos: ' . $fechaCierre); ?></b></h2></legend>        
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                <tr>
                    <th><?php echo ('usuario'); ?></th>
                    <th><?php echo ('Fecha del Abono'); ?></th>                    
                    <th><?php echo ('Cuenta'); ?></th>                    
                    <th class="text-right"><?php echo ('Valor Abono'); ?></th>
                </tr>
                <?php foreach ($arrAbonos as $abn): ?> 
                <tr>
                    <td><?php echo h($abn['cliente']); ?>&nbsp;</td>
                    <td><?php echo h($abn['fecha']); ?>&nbsp;</td>                    
                    <td><?php echo h($abn['cuenta']); ?>&nbsp;</td>                    
                    <td align="right"><?php echo h('$' . number_format($abn['valor'],2)); ?>&nbsp;</td>                    
                </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div> <br><br>
        
        <legend><h2><b><?php echo __('Detalle de Estado por Caja'); ?></b></h2></legend>  
        
        <div class="container">
            <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">              

                <?php if(!$anotDay){?>

                    <tr>
                        <th>Caja</th>
                        <th>Saldo Inicial</th>
                        <th>Ventas</th>
                        <th>Gastos</th>
                        <th>Ing. Traslados</th>
                        <th>Gas. Traslados</th>
                        <th>Abonos Prefacturas</th>
                        <th>Abonos Facturas</th>
                        <th>Total</th>
                    </tr>                     
                    <?php foreach ($estadoCuentas as $key => $val): ?> 
                    
                    <?php 
                        $saldoInicial = 0;                    
                        $saldoInicial = $val['estado_actual'];
                        $saldoInicial -= isset($val['ing_ventas']) ? $val['ing_ventas'] : 0;
                        $saldoInicial += isset($val['gastos']) ? $val['gastos'] : 0;
                        $saldoInicial -= isset($val['ing_traslados']) ? $val['ing_traslados'] : 0;
                        $saldoInicial += isset($val['gasto_traslados']) ? $val['gasto_traslados'] : 0;
                        $saldoInicial -= isset($val['abono_prefact']) ? $val['abono_prefact'] : 0;
                        $saldoInicial -= isset($val['abono_fact']) ? $val['abono_fact'] : 0;
                    ?>
                                    
                    <tr>
                        <td><?php echo h($listCuenta[$key]);?></td>                                        
                        <td align="right"><?php echo h('$' . number_format($saldoInicial,2)); ?>&nbsp;</td>                    
                        <td align="right"><?php echo h('$' . number_format((isset($val['ing_ventas']) ? $val['ing_ventas'] : 0),2)); ?>&nbsp;</td>                    
                        <td align="right"><?php echo h('$' . number_format((isset($val['gastos']) ? $val['gastos'] : 0),2)); ?>&nbsp;</td>                    
                        <td align="right"><?php echo h('$' . number_format((isset($val['ing_traslados']) ? $val['ing_traslados'] : 0),2)); ?>&nbsp;</td>                    
                        <td align="right"><?php echo h('$' . number_format((isset($val['gasto_traslados']) ? $val['gasto_traslados'] : 0),2)); ?>&nbsp;</td>                    
                        <td align="right"><?php echo h('$' . number_format((isset($val['abono_prefact']) ? $val['abono_prefact'] : 0),2)); ?>&nbsp;</td>                    
                        <td align="right"><?php echo h('$' . number_format((isset($val['abono_fact']) ? $val['abono_fact'] : 0),2)); ?>&nbsp;</td>                    
                        <td align="right"><?php echo h('$' . number_format((isset($val['estado_actual']) ? $val['estado_actual'] : 0),2)); ?>&nbsp;</td>                    
                    </tr>
                    <?php endforeach; ?>
                <?php } else { ?> 

                    <tr>
                        <th>Caja</th>
                        <th>Saldo Inicial</th>
                        <th>Ventas</th>
                        <th>Gastos</th>
                        <th>Ing. Traslados</th>
                        <th>Gas. Traslados</th>
                        <th>Abonos</th>
                        <th>Total</th>
                    </tr>   

                    <?php foreach ($cierreDiario as $cierre): ?>                         
                        <?php 
                            $total = $cierre['Cierrecaja']['saldo_inicial'];
                            $total += $cierre['Cierrecaja']['ventas'];
                            $total -= $cierre['Cierrecaja']['gastos'];
                            $total += $cierre['Cierrecaja']['traslados_ing'];
                            $total -= $cierre['Cierrecaja']['traslados_gas'];
                            $total += $cierre['Cierrecaja']['abonos'];
                        ?>                          
                        <tr>
                            <td><?php echo h($listCuenta[$cierre['Cierrecaja']['caja_id']]); ?>&nbsp;</td>
                            <td class="text-right"><?php echo h("$" . number_format($cierre['Cierrecaja']['saldo_inicial'],2)); ?>&nbsp;</td>
                            <td class="text-right"><?php echo h("$" . number_format($cierre['Cierrecaja']['ventas'],2)); ?>&nbsp;</td>
                            <td class="text-right"><?php echo h("$" . number_format($cierre['Cierrecaja']['gastos'],2)); ?>&nbsp;</td>
                            <td class="text-right"><?php echo h("$" . number_format($cierre['Cierrecaja']['traslados_ing'],2)); ?>&nbsp;</td>
                            <td class="text-right"><?php echo h("$" . number_format($cierre['Cierrecaja']['traslados_gas'],2)); ?>&nbsp;</td>
                            <td class="text-right"><?php echo h("$" . number_format($cierre['Cierrecaja']['abonos'],2)); ?>&nbsp;</td>
                            <td class="text-right"><?php echo h("$" . number_format($total,2)); ?>&nbsp;</td>                    
                        </tr>
                    <?php endforeach; ?>                                 
                <?php } ?>                
            </table>

        </div>

        <div class="container-fluid">
                <label for="comment">Observaciones:</label>
                <textarea class="form-control" rows="5" id="obs_cierre" <?php echo ($flgCierre ? '' : 'disabled'); ?>><?php echo ($obsCierre);?></textarea>
        </div><br>

        <?php if($flgCierre){?>    

            <div class="container-fluid">
                <button id="btn_cerrar" class="btn btn-primary center-block" onclick="facturarCerrarCajas();">Cerrar Cajas</button>
            </div>          
        <?php }?>
        <?php echo $this->Form->create('Reporte',array( 'controller' => 'reportes','action'=>'descargarCierreDiario')); ?>
            <fieldset>    
                <?php echo $this->Form->input('rpfechacierre', array('type' => 'hidden', 'name' => 'rpfechacierre', 'value' => $rpfechacierre))?>
                <?php echo $this->Form->input('rpcuenta', array('type' => 'hidden', 'name' => 'rpcuenta', 'value' => $rpcuenta))?>
                <?php echo $this->Form->submit('Descargar',array('class'=>'btn btn-primary')); ?>
            </fieldset>
        </form>        
</div>