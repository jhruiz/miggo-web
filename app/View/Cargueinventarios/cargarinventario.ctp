<?php 
    echo ($this->Html->script('cargarinventario/cargarinventario.js'));
?>

<style type="text/css">
    label {
            float: left;
            width: 120px;
            display: block;
            clear: left;
            text-align: left;
            cursor: hand;
        }
</style>
<?php 
    $this->layout=false;
?>

        <div class="paquetes form" id="divAsignaUsuario" text-align: center>   
            <legend><center><h4><?php echo __('Información del Producto'); ?></h4></center></legend><br><br>
            <?php echo $this->Form->create(null, array('id' => 'formCargarProductoInventario', 'type' => 'post', 'default' => false)); ?>            
            <section class="main row">
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php if($producto['Producto']['imagen'] == ""){ ?>
                                <?php echo $this->Html->image('png/image-4.png', array('alt' => 'CakePHP', 'width' => '240')); ?>  
                            <?php }else{?>
                                <img src="<?php echo $urlImg . $producto['Producto']['imagen'];?>" class="img-responsive img-rounded center-block" style="max-width: 150px; max-height: 150px" />
                            <?php }?>                            
                        </div>   
                        <div class="panel-body">
                            <b>Código: <?php echo $producto['Producto']['codigo'];?></b><br>
                            <b>Nombre: <?php echo $producto['Producto']['descripcion'];?></b><br>
                            <b>Categoría: <?php echo $producto['Categoria']['descripcion'];?></b><br>
                            <b>Marca: <?php echo $producto['Producto']['marca'];?></b><br>
                            <b>Máximo: <?php echo $producto['Producto']['existenciamaxima'];?></b><br>
                            <b>Mínimo: <?php echo $producto['Producto']['existenciaminima'];?></b><br>
                            <b>Cantidad Actual: <?php echo $existenciaActual;?></b><br>
                        </div>
                    </div>
                </div>
            
                <div class="col-md-4">                      
                    <div class="form-group form-inline">
                        <label>Cantidad</label><br>
                        <div class="input-group">
                            <span class="input-group-addon">#</span>                        
                                <?php echo $this->Form->input('cantidad', array(
                                    'label' => '', 
                                    'class' => 'form-control', 
                                    'placeholder' => 'Cantidad del Producto', 
                                    'onblur' => 'calcularValorTotal();',
                                    'disabled' => $disabled)); ?>
                        </div>                        
                    </div>
                    
                    <div class="form-group form-inline"> 
                        <label>Depósito</label>
                        <div class="input-group">
                            <?php 
                                echo $this->Form->input("deposito_id",
                                        array(
                                            'name'=>"depositos",
                                            'label' => "",
                                            'type' => 'select',
                                            'options'=>$depositos,
                                            'empty'=>'Seleccione Uno',
                                            'class' => 'form-control',
                                            'default' => $cargueInventario['Cargueinventario']['deposito_id']
                                        )
                                );
                            ?>
                        </div>
                    </div>
                    <div class="form-group form-inline"> 
                        <label>Proveedores</label>
                        <div class="input-group">

                            <select name="proveedores" id="proveedore_id" class="form-control">
                                <option value="">Seleccione uno</option>
                                <?php foreach($proveedores as $prov){ ?>
                                    <option value="<?php echo $prov['Proveedore']['id']; ?>" <?php echo $prov['Proveedore']['id'] == $cargueInventario['Cargueinventario']['proveedore_id'] ? 'selected' : '' ?> data-type="<?php echo $prov['Proveedore']['regimene_id']; ?>">
                                        <?php echo $prov['Proveedore']['nombre']; ?>
                                    </option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>          
                    <div class="form-group form-inline"> 
                        <label>Tipo Pago</label><br>
                        <div class="input-group">
                            <?php 
                                echo $this->Form->input("tipopago_id",
                                        array(
                                            'name'=>"tipopago",
                                            'label' => "",
                                            'type' => 'select',
                                            'options'=> $tipopagos,
                                            'class' => 'form-control',
                                            'disabled' => $disabled
                                        )
                                );
                            ?>
                        </div>
                    </div>                    
                    <div class="form-group form-inline"> 
                        <label>Estado</label><br>
                        <div class="input-group">
                            <?php 
                                echo $this->Form->input("estado_id",
                                        array(
                                            'name'=>"estados",
                                            'label' => "",
                                            'type' => 'select',
                                            'options'=> $estados, 
                                            'class' => 'form-control',
                                            'disabled' => $disabled
                                        )
                                );
                            ?>
                        </div>
                    </div>  
                <?php foreach ($impuestos as $imp){ ?>
                    <div class="form-group form-inline"> 
                        <b><?php echo $imp['Impuesto']['descripcion'];?></b> <input type="checkbox" name="data[impuestos][<?php echo $imp['Impuesto']['id'];?>]" value="<?php echo $imp['Impuesto']['id'];?>"><br>
                    </div>                           
               <?php } ?>                    
                </div>
                <div class="col-md-4">
                    <div class="form-group form-inline"> 
                        <label><span id="val_prod">Costo Unitario</span></label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>                    
                            <?php echo $this->Form->input('costoproducto', array(
                                'label' => '', 
                                'class' => 'form-control numericPrice', 
                                'placeholder' => 'Valor Unitario', 
                                'onblur' => 'calcularValorTotal();',
                                'value' => $cargueInventario['Cargueinventario']['costoproducto']
                                )
                                ); ?>
                        </div>
                    </div>  
                    <div class="form-group form-inline"> 
                        <label>Valor Total</label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>                    
                            <?php echo $this->Form->input('costototal', array('label' => '', 'class' => 'form-control numericPrice', 'placeholder' => 'Valor Total', 'disabled' => 'disabled')); ?>
                        </div>
                    </div>                    
                    <div class="form-group form-inline"> 
                        <label>Precio Máximo</label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>                    
                            <?php echo $this->Form->input('preciomaximo', array(
                                'label' => '', 
                                'class' => 'form-control numericPrice', 
                                'placeholder' => 'Precio Máximo',
                                'value' => $cargueInventario['Cargueinventario']['preciomaximo']
                                )
                                ); ?>
                        </div>
                    </div> 
                    <div class="form-group form-inline"> 
                        <label>Precio Mínimo</label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>                    
                            <?php echo $this->Form->input('preciominimo', array(
                                'label' => '', 
                                'class' => 'form-control numericPrice', 
                                'placeholder' => 'Precio Mínimo',
                                'value' => $cargueInventario['Cargueinventario']['preciominimo']
                                )
                                ); ?>
                        </div>
                    </div> 
                    <div class="form-group form-inline"> 
                        <label>Precio Venta</label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>                    
                            <?php echo $this->Form->input('precioventa', array(
                                'label' => '', 
                                'class' => 'form-control numericPrice', 
                                'placeholder' => 'Precio de Venta',
                                'value' => $cargueInventario['Cargueinventario']['precioventa']
                                )
                                ); ?>
                        </div>
                    </div>  
                    <div class="form-group form-inline"> 
                        <label>Número de Factura</label>
                        <div class="input-group">
                            <span class="input-group-addon">#</span>                    
                            <?php echo $this->Form->input('numerofactura', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Número de Factura')); ?>
                        </div>
                    </div>                     
                </div>
            </section>    
                    <?php                                                
                        echo $this->Form->input("producto_id",array('id' => "producto_id",'type' => 'hidden','value'=>$producto_id));
                        echo $this->Form->input("empresa_id",array('id' => "empresa_id",'type' => 'hidden','value'=>$empresa_id));
                        echo $this->Form->input("usuario_id",array('id' => "usuario_id",'type' => 'hidden','value'=>$usuario_id));                            
                        echo $this->Form->input("inventario",array('id' => "inventario",'type' => 'hidden','value'=>$producto['Producto']['inventario']));                            

                    ?>
            <div class="container-fluid">
                <div class="text-center">
                    <div class="btn-group">
                        <button  id="btn_guardarEst" class="btn btn-primary" onclick="guardarInfoProducto()">Pre Cargar</button>
                        <button  id="btn_guardarEst" class="btn btn-primary" onclick="cancelarCargueProducto()">Cancelar</button>
                    </div>                    
                </div>

            </div>
        </form>
        </div>
