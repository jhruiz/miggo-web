<?php echo ($this->Html->script('cuentasclientes/imprimirabonoscuentascliente.js')); ?>
<?php $this->layout='inicio'; ?>
<div class="abonoscuentasclientes index">
	<legend><h2><b><?php echo __('Abonos'); ?></b></h2></legend>    
	<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '35', 'id' => 'menuvert'))?>    
        <div class="table-responsive">
            <div class="container-fluid"> 
                <input id="ccId" type="hidden" value="<?php echo ($id);?>">
                <input id="clienteCel" type="hidden" value="<?php echo !empty($abonos['0']['CL']['celular']) ? $abonos['0']['CL']['celular'] : "";?>">
                <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                <tr>
                                <th><?php echo ('Usuario'); ?></th>
                                <th><?php echo ('Cuenta'); ?></th>                                                                
                                <th><?php echo ('Cliente'); ?></th>
                                <th><?php echo ('Fecha'); ?></th>                                
                                <th><?php echo ('Valor'); ?></th>
                </tr>
                <?php $ttalAbonos = 0; ?>
                <?php foreach ($abonos as $ab): ?>
                <?php $ttalAbonos += $ab['Abonofactura']['valor']; ?>
                <tr>
                    <td><?php echo h($ab['U']['nombre']); ?>&nbsp;</td>
                    <td><?php echo h($ab['C']['descripcion']); ?>&nbsp;</td>
                    <td><?php echo h($ab['CL']['nombre']); ?>&nbsp;</td>
                    <td><?php echo h($ab['Abonofactura']['created']); ?>&nbsp;</td>
                    <td class="text-right"><?php echo h("$" . number_format($ab['Abonofactura']['valor'])); ?>&nbsp;</td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td><b>Total</b></td>
                    <td class="text-right"><b><?php echo h("$" . number_format($ttalAbonos));?></b></td>
                </tr>
                </table>
            </div>
        </div>
        <div class="container-fluid">            
            <div class="col-md-1"><a href="#" class="btn btn-primary active pull-lefth" role="button" aria-pressed="true" id="impAbono">Imprimir</a></div>
            <div class="col-md-1">
                <a href="#" class="wppSendPF" target="">
                    <img src="<?php echo $urlImgWP; ?>" class="img-responsive" width="35">            
                </a>
            </div>
            <div class="col-md-10"></div>
        </div>            
</div>