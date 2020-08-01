<?php echo ($this->Html->script('trasladoinventario/trasladoinventario.js')); ?>
<?php 
$this->layout='inicio';     
?>
<div class="trasladoinventarios index">
	<legend><h2><b><?php echo __('Traslado de Inventario'); ?></b></h2></legend>
	<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '38', 'id' => 'menuvert'))?>
        <div>
            <div class="container">                  
                <div class="row">
                    <div class="col-md-3">
                        <?php echo $this->Form->input('depositoorigen_id', array('label' => 'Deposito Origen', 'options' => $depositos, 'class' => 'form-control', 'empty' => 'Seleccione Uno', 'onchange' => 'habilitarBuscarProducto();'));?>
                    </div>
                    <div class="col-md-3">
                        <?php echo $this->Form->input('depositodestino_id', array('label' => 'Deposito Origen', 'options' => $depositos, 'class' => 'form-control', 'empty' => 'Seleccione Uno', 'onchange' => 'habilitarBuscarProducto();'));?>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">  
                            <label>Buscar Producto</label><br>               
                                <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId)); ?>
                                <?php echo $this->Form->input('usuario_id', array('type' => 'hidden', 'value' => $usuarioId)); ?>
                                <?php echo $this->Form->input('buscarproducto', array('label' => false, 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Ingresar Nombre o C¨®digo del Producto', 'onkeyup' => 'fnObtenerDatosProducto(event);')); ?>
                            <div id="datosProducto" style="position:absolute; z-index:1;"></div> <br>                               
                        </div>  
                    </div>                                      
                </div>        
                
                
                <table id="trasInventario" cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                    <tr>
                                    <th><?php echo h('Producto'); ?></th>
                                    <th><?php echo h('Cantidad'); ?></th>
                                    <th class="actions">&nbsp;</th>
                    </tr>                   
                </table>
            </div>
        </div>
</div><br><br>
<div class="container">
    <button id="btn_descargar" class="btn btn-primary center-block" onclick="aprobarTrasladoInventario();">Aprobar Traslado</button>
</div>  
<legend>&nbsp;</legend>
<div id="notaTraslado"></div>