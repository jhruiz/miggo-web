<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('cierrecajas/cierrecajas.js')); ?>
<div class="paises index">
    
        <?php echo $this->Form->create('Cierrecajas',array('action'=>'search','method'=>'post', 'class' => 'form-inline'));?>
        <legend><h2><b><?php echo __('Buscar Cierres de Caja'); ?></b></h2></legend>      
        <div class="row" style="margin-bottom: 20px;">
                
            <div class="form-group">
                <label for="fechacierre">Fecha</label><br>
                <input name="data[Cierrecaja][Fecha]" id="fechacierre" autocomplete="off" class="date form-control" placeholder="Fecha de cierre" type="text">
            </div>
            
            <div class="form-group">
                <label for="CierrecajasCuenta">Cuentas</label>
                <?php echo $this->Form->input('cuenta', array('label' => '', 'name' => 'data[Cierrecaja][Cuenta]', 'empty' => 'Seleccione una', 'type' => 'select', 'options' => $listCuentas, 'class' => 'form-control'));?>
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
            
	<legend><h2><b><?php echo __('Cierre de cajas'); ?></b></h2></legend>
        
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                                <th><?php echo ('Caja'); ?></th>
                                <th><?php echo ('Saldo inicial'); ?></th>
                                <th><?php echo ('Ventas'); ?></th>
                                <th><?php echo ('Gastos'); ?></th>
                                <th><?php echo ('Ing. Traslados'); ?></th>
                                <th><?php echo ('Gas. Traslados'); ?></th>
                                <th><?php echo ('Abonos'); ?></th>
                                <th><?php echo ('Total'); ?></th>
                </tr>
                <?php $ttalFinal = 0; ?>
                <?php foreach ($cierreDiario as $cierre): ?>
                <?php 
                    $total = $cierre['Cierrecaja']['saldo_inicial'];
                    $total += $cierre['Cierrecaja']['ventas'];
                    $total -= $cierre['Cierrecaja']['gastos'];
                    $total += $cierre['Cierrecaja']['traslados_ing'];
                    $total -= $cierre['Cierrecaja']['traslados_gas'];
                    $total += $cierre['Cierrecaja']['abonos'];
                    $ttalFinal += $total;
                ?>
                <tr>
                    <td><?php echo h($listCuentas[$cierre['Cierrecaja']['caja_id']]); ?>&nbsp;</td>
                    <td class="text-right"><?php echo h("$" . number_format($cierre['Cierrecaja']['saldo_inicial'],2)); ?>&nbsp;</td>
                    <td class="text-right"><?php echo h("$" . number_format($cierre['Cierrecaja']['ventas'],2)); ?>&nbsp;</td>
                    <td class="text-right"><?php echo h("$" . number_format($cierre['Cierrecaja']['gastos'],2)); ?>&nbsp;</td>
                    <td class="text-right"><?php echo h("$" . number_format($cierre['Cierrecaja']['traslados_ing'],2)); ?>&nbsp;</td>
                    <td class="text-right"><?php echo h("$" . number_format($cierre['Cierrecaja']['traslados_gas'],2)); ?>&nbsp;</td>
                    <td class="text-right"><?php echo h("$" . number_format($cierre['Cierrecaja']['abonos'],2)); ?>&nbsp;</td>
                    <td class="text-right"><?php echo h("$" . number_format($total,2)); ?>&nbsp;</td>                    
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="7" class="text-right"><b>TOTAL</b></td>
                    <td class="text-right"><b><?php echo h("$" . number_format($ttalFinal,2)); ?>&nbsp;</b></td>
                </tr>
                </table>
            </div>            
        </div>
        <div class="container">
                <label for="comment">Observaciones:</label>
                <textarea class="form-control" rows="5" id="obs_cierre" disabled='disabled'><?php echo ($obsCierre);?></textarea>
        </div><br>        
</div>
<?php echo $this->Form->create('Reporte',array( 'controller' => 'reportes','action'=>'descargarListaCierreDiario')); ?>
    <fieldset>    
        <?php echo $this->Form->input('rpfech', array('type' => 'hidden', 'name' => 'rpfech', 'value' => $fecha))?>
        <?php echo $this->Form->input('cajaId', array('type' => 'hidden', 'name' => 'cajaId', 'value' => $cuenta))?>        
        <?php echo $this->Form->submit('Descargar',array('class'=>'btn btn-primary')); ?>
    </fieldset>
</form><br><br>