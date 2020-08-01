<?php $this->layout=false; ?>
<?php echo ($this->Html->script('cargueinventario/nuevoproducto.js')); ?>
    <div class="productos form">

    <?php echo $this->Form->create(null, array('type' => 'file', 'class' => 'form-horizontal', 'default' => false)); ?>
    <section class="main row">
        <legend><center><h4><?php echo __('Agregar Producto'); ?></h4></center></legend>
            <div class="col-md-1"></div>
            <div class="col-md-5">          
                <div class="form-group form-inline"> 
                    <label>Código</label><br>
                    <?php echo $this->Form->input('codigo', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Código del Producto')); ?>                   
                </div>
                <div class="form-group form-inline"> 
                    <label>Referencia</label><br>
                    <?php echo $this->Form->input('referencia', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Referencia del Producto')); ?>                   
                </div>
                <div class="form-group form-inline">
                    <label>Nombre</label><br>
                    <?php echo $this->Form->input('descripcion', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre del Producto')); ?>
                </div>
                <div class="form-group form-inline"> 
                    <label>Categoría</label><br>
                    <?php echo $this->Form->input('categoria_id', array('label' => '', 'class' => 'form-control')); ?>
                </div>
                <div class="form-group form-inline">
                    <label>Marca</label><br>
                    <?php echo $this->Form->input('marca', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Marca del Producto')); ?>
                </div>
                <div class="form-group form-inline"> 
                    <label>Mostrar en Catálogo</label>
                    <?php echo $this->Form->input('mostrarencatalogo', array('label' => '', 'type' => 'checkbox', 'class' => 'form-control')); ?>
                    <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId))?>
                </div>                  
            </div>
            <div class="col-md-5">
                <div class="form-group form-inline"> 
                    <label>Existencia Mínima</label><br>
                    <?php echo $this->Form->input('existenciaminima', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Cantidad Mínima Permitida')); ?>
                </div>
                <div class="form-group form-inline"> 
                    <label>Existencia Máxima</label><br>
                    <?php echo $this->Form->input('existenciamaxima', array('label' =>  '', 'class' => 'form-control', 'placeholder' => 'Cantidad Máxima Permitida')); ?>
                </div>
                <div class="form-group form-inline"> 
                    <label>Imagen</label><br>
                    <?php echo $this->Form->input('imagen', array('label' => '', 'type' => 'file')); ?>
                    <p class="help-block">Máximo 1MB</p>
                </div>                
            </div>
            <div class="col-md-1"></div>
    </section>            
            <br>        
        <div class="container-fluid">
            <button  id="btn_guardPdr" class="btn btn-primary center-block" onclick="guardarNuevoProducto()">Guardar</button>                         
        </div>                       
    </div>