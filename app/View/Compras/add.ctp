<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('compras/add'));?>
<div class="compras form">
<?php echo $this->Form->create('Compra', array('class' => 'form-inline')); ?>
	<fieldset>
            <legend><h2><b><?php echo __('Agregar Compra'); ?></b></h2></legend>
            <input type="hidden" name="usuario_id" id="usuario_id" value="<?php echo $userId;?>">
            <input type="hidden" name="imp_val" id="imp_val" value="<?php echo $imp['0']['Impuesto']['valor'];?>">
            <div class="row">
                <div class="form-group">
                    <label for="proveedore_id">Proveedor</label><br>
                    <select name="proveedore_id" id="proveedore_id" class="form-control">
                        <option value="">Seleccione</option>
                      <?php foreach ($listProv as $key => $val){?>
                        <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                      <?php } ?>
                    </select>                    
                </div>
            </div><br>
            
            <div class="row">
                <div class="form-group">
                    <label for="fechaFactura">Fecha Factura</label><br>
                    <input name="fechaFactura" class="date form-control" placeholder="Fecha Factura" type="text" id="fechaFactura" value="" autocomplete="off">                        
                </div>            
            </div><br>
            
            <div class="row">
                <div class="form-group">
                    <label for="numFactura">Número Factura</label><br>
                    <input name="numFactura" class="form-control" placeholder="Número Factura" type="text" id="numFactura" value="" autocomplete="off">                        
                </div>            
            </div>            
	</fieldset><br><br>
    
    <fieldset>
        <legend><h5><b><?php echo __('Categorias'); ?></b></h5></legend>    
        <div class="row">
            <div class="form-group">
                <label for="categoria_id">Categoría</label><br>
                <select name="categoria_id" id="categoria_id" class="form-control">
                    <option value="">Seleccione</option>
                  <?php foreach ($listCatCom as $key => $val){?>
                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                  <?php } ?>
                </select>                    
            </div>
            
            <div class="form-group">
                <label for="valCat">Valor</label><br>
                <input name="valCat" class="form-control numericPrice" placeholder="Valor Categoría" type="text" id="valCat" value="">                        
            </div><br><br>
            
            <div>
                <button type="button" class="btn btn-primary" id="addCategory">Agregar</button>    
            </div>
        
        </div><br>        
        
        
        <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>			
                    <th><?php echo h('Descripción'); ?></th>
                    <th><?php echo h('Valor'); ?></th>
                    <th>&nbsp;</th>
                </tr>                
            </thead>
            <tbody id="category"></tbody>
            <tbody id="subTtalAntImp">
                <tr><td><b>SUBTOTAL</b></td><td style="border-top:2pt solid black;"><b>$0</b></td><td>&nbsp;</td></tr>
            </tbody>
            <tbody id="IvaVal">
                <tr><td><b>IVA</b></td><td>$0</td><td>&nbsp;</td></tr>
            </tbody>
            <tbody id="retefuenteVal" bgcolor="#FF0000">
                <tr><td><b>RETEFUENTE</b></td>
                    <td>($<span class="sp_retefuente">0</span>)</td>
                    <td>
                        <button type='button' id='addRetefuente' class='btn btn-success' aria-label='Left Align'> 
                            <span class='fa fa-plus-circle fa-lg btn-xs' aria-hidden='true'></span>
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody id="reteicaVal">
                <tr><td><b>RETEICA</b></td><td>($<span class="sp_reteica">0</span>)</td>
                    <td>
                        <button type='button' id='addReteIca' class='btn btn-success' aria-label='Left Align'> 
                            <span class='fa fa-plus-circle fa-lg btn-xs' aria-hidden='true'></span>
                        </button>
                    </td>
                </tr>                    
            </tbody>
            <tbody id="ttalVal">
                <tr><td><b>TOTAL</b></td><td style="border-top:2pt solid black;"><b>$<span class="ttal">0</span></b></td><td>&nbsp;</td></tr>
            </tbody>
        </table>
    </fieldset><br><br>


    <fieldset>

        <legend><h5><b><?php echo __('Pago'); ?></b></h5></legend>
        <div class="row">
            <div class="form-group">
                <label for="tipopago_id">Métodos de pago</label><br>
                <select name="tipopago_id" id="tipopago_id" class="form-control">
                    <option value="">Seleccione</option>
                  <?php foreach ($tipoPago as $key => $val){?>
                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                  <?php } ?>
                </select>                    
            </div>

            <div class="form-group">
                <label for="valTipoPago">Valor</label><br>
                <input name="valuePaymenth" class="form-control numericPrice" placeholder="Valor Pago" type="text" id="valuePaymenth" value="" disabled='disabled'>                        
            </div>            

            <div class="form-group">
                <label for="categoria_id">Cuenta a debitar</label><br>
                <select name="cuenta_id" id="cuenta_id" class="form-control" disabled='disabled'>
                    <option value="">Seleccione</option>
                  <?php foreach ($cuentas as $key => $val){?>
                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                  <?php } ?>
                </select>                    
            </div><br><br>
            
            <div>
                <button type="button" class="btn btn-primary" id="addPayment">Agregar</button>    
            </div><br>

            <div class="col-md-5">
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                    <thead>
                        <tr>			
                            <th><?php echo h('Descripción'); ?></th>
                            <th><?php echo h('Cuenta a debitar'); ?></th>
                            <th><?php echo h('Valor'); ?></th>
                            <th>&nbsp;</th>
                        </tr>                
                    </thead>
                    <tbody id="metPagos">
                    </tbody>
                    <tbody id="ttalPago">
                        <tr><td><b>TOTAL PAGO</b></td><td>&nbsp;</td><td style="border-top:2pt solid black;"><b>$<span class="ttalPay">0</span></b></td><td></td></tr>
                    </tbody>                    
                    <tbody id="ttalFactura">
                        <tr><td><b>TOTAL FACTURA</b></td><td>&nbsp;</td><td style="border-top:2pt solid black;"><b>$<span class="ttalFact">0</span></b></td><td></td></tr>
                    </tbody>
                    <tbody id="ttalDiff">
                        <tr><td><b>RESTANTE</b></td><td>&nbsp;</td><td style="border-top:2pt solid black;"><b>$<span class="ttalDiffPay">0</span></b></td><td></td></tr>
                    </tbody>
                </table>            
            </div>
                    
        </div><br>          
    
    </fieldset>

    <div>
        <button type="button" class="btn btn-primary" id="guardarCompra">Guardar</button> 
    </div>

        
        
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Compras'), array('action' => 'index')); ?></li>
	</ul>
</div>
<div class="dv_reteica"></div>
<div class="dv_retefuente"></div>
