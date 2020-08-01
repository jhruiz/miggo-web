<?php $this->layout='inicio'; ?>
    <div class="productos view">
        <legend><h2><b><?php echo __('Producto'); ?></b></h2></legend>
        <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '4', 'id' => 'menuvert'))?> 
            <section class="main row">                            
                <div class="col-md-4"> 
                    <?php if($producto['Producto']['imagen'] == ""){ ?>
                        <?php echo $this->Html->image('png/image-4.png', array('alt' => 'CakePHP', 'width' => '400', 'height' => '500')); ?>  
                    <?php }else{?>
                        <img src="<?php echo $urlImagen . $producto['Producto']['imagen'];?>" class="img-responsive img-thumbnail">
                    <?php }?>                        
                </div>
                <div class="col-md-4">
                    <dl>
                        <dt class="text-info"><?php echo __('Código del Producto'); ?></dt>
                            <dd>
                                    <?php echo h($producto['Producto']['codigo']); ?>
                                    &nbsp;
                            </dd><br>
                        <dt class="text-info"><?php echo __('Referencia del Producto'); ?></dt>
                            <dd>
                                    <?php echo h($producto['Producto']['referencia']); ?>
                                    &nbsp;
                            </dd><br>
                        <dt class="text-info"><?php echo __('Nombre del Producto'); ?></dt>
                            <dd>
                                    <?php echo h($producto['Producto']['descripcion']); ?>
                                    &nbsp;
                            </dd><br>
                        <dt class="text-info"><?php echo __('Categoria'); ?></dt>
                            <dd>
                                    <?php echo h($producto['Categoria']['descripcion']); ?>
                                    &nbsp;
                            </dd><br>
                        <dt class="text-info"><?php echo __('Marca'); ?></dt>
                            <dd>
                                    <?php echo h($producto['Producto']['marca']); ?>
                                    &nbsp;
                            </dd><br>
                        <dt class="text-info"><?php echo __('Existencia Mínima'); ?></dt>
                            <dd>
                                    <?php echo h($producto['Producto']['existenciaminima']); ?>
                                    &nbsp;
                            </dd><br>
                        <dt class="text-info"><?php echo __('Existencia Máxima'); ?></dt>
                            <dd>
                                    <?php echo h($producto['Producto']['existenciamaxima']); ?>
                                    &nbsp;
                            </dd><br>
                        <dt class="text-info"><?php echo __('Mostrar en Catálogo?'); ?></dt>
                            <dd>
                                    <?php if ($producto['Producto']['mostrarencatalogo'] == '1'){echo 'SI'; }else{echo 'NO';} ?>
                                    &nbsp;
                            </dd><br>
                    </dl>  
                </div>              
            </section>
    </div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Producto'), array('action' => 'edit', $producto['Producto']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Productos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Producto'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista de Categorías'), array('controller' => 'categorias', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Categoría'), array('controller' => 'categorias', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Cargue de Inventarios'), array('controller' => 'cargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cargue de Inventario'), array('controller' => 'cargueinventarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Descargue de Inventarios'), array('controller' => 'descargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Descargue de Inventario'), array('controller' => 'descargueinventarios', 'action' => 'add')); ?> </li>
	</ul>
</div>