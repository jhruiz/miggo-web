<?php 
    echo ($this->Html->script('cargueinventario/cargueinventario.js'));
    echo $this->Html->script('utilsjs/utilArchivos.js');
?>
<?php $this->layout='inicio'; ?>
<div class="container body">
<div class="main_container">
      <div class="x_panel">
<div class="cargueinventarios form">
     <div class="x_title">
                    <h2><?php echo __('Selección de Productos'); ?></h2>
                    <ul class="nav navbar-right panel_toolbox">
                  
                  </li>
                  <li class="dropdown">
                   
                  </li>
                
                  </li>
                </ul>
</div>  
	<fieldset>
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '11', 'id' => 'menuvert'))?> 
                <div id="div_exito" class="alert alert-info" style="display:none;"></div>
                <div id="div_error" class="alert alert-danger" style="display:none;"></div>
                <div class="row">
                    <div class="col-md-12">
                        <div role="tabpanel">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#cuadro" aria-controls="cuadro" data-toggle="tab" role="tab">Cuadro</a></li>
                                <!--<li role="presentation"><a href="#listado" aria-controls="listado" data-toggle="tab" role="tab">Listado</a></li>-->
                            </ul>
                            <div class="tab-content">
                                <br><br>
                                <!--Aqui inicia el panel para el cargue en el inventario en cuadro-->
                                <div role="tabpanel" class="tab-pane active" id="cuadro">
                                    <div class="container">                                        
                                        <div class="btn-group">
                                            <button id="butCargarInventarioUp" class="btn btn-primary" onclick="cargueInventarioCuadro();" disabled="disabled">Cargar Inventario</button>
                                            <button id="butNuevoProducto" class="btn btn-primary" onclick="nuevoProducto();">Nuevo Producto</button>
                                            <button id="butCargueParcial" class="btn btn-primary" onclick="verCargueParcial();">Ver Cargue Parcial</button>                                           
                                            <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId)); ?>
                                        </div><br><br>
                                    </div>    
                                                                                
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">  
                                                    <label>Producto</label><br>                                
                                                        <?php echo $this->Form->input('buscarproducto', array('label' => false, 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Selección de Producto', 'onkeyup' => 'fnObtenerDatosProducto(event);')); ?>
                                                    <div id="datosProducto" style="position:absolute; z-index:1;"></div> <br>                               
                                                </div>  
                                            </div>
                                        </div>
                                    </div>
                          
                                    
                                    <?php $contador = 6; $k = 0;?>
                                    <?php for($i = 0; $i < ceil(count($productos)/6); $i++){?>
                                    <div class="row">
                                        <?php for($k; $k < $contador; $k++){?>
                                            <?php if (isset($productos[$k])){?>
                                                <div class="col-md-2">     
                                                 <div class="thumbnail">                                    
                                                    <div id="dv_<?php echo $productos[$k]['Producto']['id'];?>" class="">
                                                        <div class="image view view-first">
    <?php if(empty($productos[$k]['Producto']['imagen'])){ ?>
 <img src="/app/torque/img/png/multiply.png" class="img-responsive img-rounded center-block" style="max-width: 150px; max-height: 150px" />  

   <?php }else{   ?>                              
                                                            <img src="<?php echo $urlImg . $productos[$k]['Producto']['empresa_id'] . '/' . $productos[$k]['Producto']['imagen'];?>" class="img-responsive img-rounded center-block" style="max-width: 150px; max-height: 150px" />               <?php }   ?>                                          
                                                        </div>  
                                                        <div class="mask">
                                                                <strong><?php echo $productos[$k]['Producto']['descripcion']?></strong><br>   
                                                                <input type="checkbox" class="chkPdr" name="<?php echo $productos[$k]['Producto']['id'];?>" id="chk_<?php echo $productos[$k]['Producto']['id'];?>" value="<?php echo $productos[$k]['Producto']['id'];?>" onclick="habilitarCargueInventario(this);"> Agregar<br>
                                                        </div>  
                                                    </div>
</div><!--thumbnail-->
                                                </div>
                                            <?php } else{break;}?>
                                        <?php } $contador = $contador + 6;?>
                                    </div>
                                    <?php }?> 
                                    <input type="hidden" id="usuarioId" value="<?php echo $usuarioId;?>">
                                    <input type="hidden" id="empresaId" value="<?php echo $empresaId;?>">
                                    <input type="hidden" id="urlImg" value="<?php echo $urlImg;?>">
                                                                       
                                    <div class="container">
                                        <br>
                                        <div class="btn-group">
                                            <button id="butCargarInventarioDown" class="btn btn-primary" onclick="cargueInventarioCuadro();" disabled="disabled">Cargar Inventario</button>
                                            <button id="butNuevoProducto" class="btn btn-primary" onclick="nuevoProducto();">Nuevo Producto</button>
                                            <?php echo $this->Html->link("Ver Cargue Parcial", array('controller' => 'precargueinventarios','action'=> 'index'), array( 'class' => 'button btn btn-primary', 'style' => 'color:white;'))?> 
                                        </div>
                                    </div>
                                    <div class="clearfix visible-sm-block"></div>
                                </div>
                                <!--Aqui finaliza el panel para el cargue en el inventario en cuadro-->
                                
                                
                                
                                <!--Aqui inicial el panel para el cargue en el inventario en listado--> 
                                <div role="tabpanel" class="tab-pane" id="listado">
                                    <h3>Se muestran las imagenes en un listado</h3>
                                </div>                                    
                                <!--Aqui finaliza el panel para el cargue en el inventario en listado-->
                            </div>
                        </div>
                    </div>
                </div><br><br>                               
	</fieldset>
</div>

<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>

		<li><?php echo $this->Html->link(__('Lista de Cargue Inventarios'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Productos'), array('controller' => 'productos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Producto'), array('controller' => 'productos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Depositos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Deposito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Impuestos'), array('controller' => 'impuestos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Impuesto'), array('controller' => 'impuestos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Estado'), array('controller' => 'estados', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Tipo de Pagos'), array('controller' => 'tipopagos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Tipo de Pago'), array('controller' => 'tipopagos', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div id="div_inventario"></div>
<div id="div_producto"></div>
</div><!--termina panel-->
</div><!-- -->
</div><!-- -->