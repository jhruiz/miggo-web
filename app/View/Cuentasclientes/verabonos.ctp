<?php 
echo ($this->Html->script('cuentasclientes/imprimirabonoscuentascliente.js'));
echo ($this->Html->script('abonos/gestionabonos.js'));
?>
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
                                <th><?php echo ('Acciones'); ?></th>
                </tr>
                <?php $ttalAbonos = 0; ?>
                <?php foreach ($abonos as $ab): ?>
                <?php $ttalAbonos += $ab['Abonofactura']['valor']; ?>
                <tr id="fila-<?php echo h($ab['Abonofactura']['id']); ?>">
                    <td><?php echo h($ab['U']['nombre']); ?>&nbsp;</td>
                    <td><?php echo h($ab['C']['descripcion']); ?>&nbsp;</td>
                    <td><?php echo h($ab['CL']['nombre']); ?>&nbsp;</td>
                    <td><?php echo h($ab['Abonofactura']['created']); ?>&nbsp;</td>
                    <td class="text-right valor"><?php echo h("$" . number_format($ab['Abonofactura']['valor'])); ?>&nbsp;</td>

                    <td class="actions">
                        <i class="fa fa-pencil fa-lg text-primary" 
                            id="<?php echo h($ab['Abonofactura']['id']); ?>" 
                            data-valor="<?php echo($ab['Abonofactura']['valor']); ?>" 
                            data-cuenta="<?php echo($ab['C']['id']); ?>" 
                            data-fecha="<?php echo($ab['Abonofactura']['created']); ?>"  
                            style="cursor: pointer;" onclick="setearEditarAbono(this);"></i>

                        <i class="fa fa fa-trash-o fa-lg text-danger" 
                            id="<?php echo h($ab['Abonofactura']['id']); ?>" 
                            data-valor="<?php echo($ab['Abonofactura']['valor']); ?>" 
                            data-cuenta="<?php echo($ab['C']['id']); ?>"
                            data-factura="<?php echo($ab['F']['id']); ?>"
                            style="cursor: pointer;" 
                            onclick="eliminarAbono(this);"></i>
                    </td>

                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td><b>Total</b></td>
                    <td class="text-right" id="totalesAbonos"><b><?php echo h("$" . number_format($ttalAbonos));?></b></td>
                    <td>&nbsp;</td>
                </tr>
                </table>
            </div>
        </div>

        <div class="container d-flex justify-content-center align-items-center" style="width: 50%; margin: 0 auto; padding: 20px;" id="formEditarAbono">
            <h2 class="mt-4"><b>Datos del abono</b></h2>
            <form class="form">
                <!-- Campo para ingresar valores en pesos colombianos -->
                <div class="form-group mr-2">
                    <label for="fechaAbono">Fecha del abono:</label>
                    <input type="text" class="form-control form-control-sm" id="fechaAbono" disabled="disabled">
                </div>

                <!-- Input tipo select -->
                <div class="form-group mr-2">
                    <label for="opciones">Cuenta:</label>
                    <select class="form-control form-control-sm" id="cuenta">
                    <?php foreach ($cuentas as $key => $val): ?>
                        <option value="<?php echo ($key); ?>"><?php echo ($val); ?></option>
                    <?php endforeach;?>
                    </select>
                </div>

                <!-- Campo para ingresar valores en pesos colombianos -->
                <div class="form-group mr-2">
                    <label for="valorAbono">Valor del abono:</label>
                    <input type="text" class="form-control form-control-sm" id="valorAbono" placeholder="Valor del abono">
                </div>
                
                <!-- Campo para el valor inicial del abono-->
                <div>
                    <input type="hidden" id="valorAbonoHidden">
                </div>

                
                <!-- Campo para el id del abono-->
                <div>
                    <input type="hidden" id="idAbono">
                </div>

                <!-- Campo para el id de la factura-->
                <div>
                    <input type="hidden" id="idFactura" value="<?php echo($abonos['0']['F']['id']);?>">
                </div>

                <!-- Botón de actualizar y limpiar -->
                <div>
                <button type="button" class="btn btn-primary btn-sm" id="btnActAbono">Actualizar</button>
                <button type="button" class="btn btn-primary btn-sm" id="btnHide">Limpiar</button>
                </div>
            </form>
        </div>

        <div class="container-fluid">            
            <div class="col-md-2"><a href="/miggo-web/cuentasclientes" class="btn btn-primary active pull-lefth" role="button" aria-pressed="true">Volver a cuentas por cobrar</a></div>
            <div class="col-md-1"><a href="#" class="btn btn-primary active pull-lefth" role="button" aria-pressed="true" id="impAbono">Imprimir</a></div>
            <div class="col-md-1">
                <a href="#" class="wppSendPF" target="">
                    <img src="<?php echo $urlImgWP; ?>" class="img-responsive" width="35">            
                </a>
            </div>
            <div class="col-md-8"></div>
        </div>            
</div>