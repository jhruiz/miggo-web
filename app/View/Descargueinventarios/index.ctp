<?php echo ($this->Html->script('descargueinventario/descargueinventario.js')); ?>
<?php 
$this->layout='inicio';     
?>
<div class="descargueinventarios index">
	<legend><h2><b><?php echo __('Descargue de Inventario'); ?></b></h2></legend>
	<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '18', 'id' => 'menuvert'))?>
        <div class="">
            <div class="container">  
                
                <div class="row">
                    <div class="col-md-4">
                        <?php echo $this->Form->input('deposito_id', array('class' => 'form-control', 'empty' => 'Seleccione Uno', 'onchange' => 'habilitarBuscarProducto();'));?>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">  
                            <label>Producto</label><br>               
                                <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId)); ?>
                                <?php echo $this->Form->input('usuario_id', array('type' => 'hidden', 'value' => $usuarioId)); ?>
                                <?php echo $this->Form->input('buscarproducto', array('label' => false, 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Ingresar Nombre o C¨®digo del Producto', 'onkeyup' => 'fnObtenerDatosProducto(event);')); ?>
                            <div id="datosProducto" style="position:absolute; z-index:1;"></div> <br>                               
                        </div>  
                    </div>              
                    <div class="col-md-2">&nbsp;</div>                        
                </div>             
                
                <table id="descInventario" cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                    <tr>
                                    <th><?php echo h('Producto'); ?></th>
                                    <th><?php echo h('Cantidad'); ?></th>
                                    <th class="actions">&nbsp;</th>
                    </tr>                   
                </table>
            </div>
        </div>
</div><br><br>
<div class="container-fluid">
    <button id="btn_descargar" class="btn btn-primary center-block" onclick="aprobarDescargueInventario();">Aprobar Descargue</button>
</div>  
<legend>&nbsp;</legend>
<div id="notaDescargue"></div>
