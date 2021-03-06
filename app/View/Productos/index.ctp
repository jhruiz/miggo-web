<?php $this->layout = 'inicio';?>


<div class="productos index">

    <?php echo $this->Form->create('Productos', array('action' => 'search', 'method' => 'post', 'class' => 'form-inline')); ?>
    <legend>
        <h2><b><?php echo __('Buscar Productos'); ?></b></h2>
    </legend>
    <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '4', 'id' => 'menuvert')) ?>

    <div class="form-group">
        <label for="codigo">Código</label><br>
        <input name="codigo" id="codigo" class="form-control" placeholder="Código del Producto"
            value="<?php echo ($codigo); ?>" type="text">
    </div>

    <div class="form-group">
        <label for="referencia">Referencia</label><br>
        <input name="referencia" id="referencia" class="form-control" placeholder="Referencia del Producto" type="text"
            value="<?php echo ($referencia); ?>">
    </div>

    <div class="form-group">
        <label for="nombre">Nombre</label><br>
        <input name="nombre" id="nombre" class="form-control" placeholder="Nombre del Producto" type="text"
            value="<?php echo ($nombre); ?>">
    </div>

    <div class="form-group">
        <label for="ProductosCategoria">Categoría</label>
        <?php echo $this->Form->input('categoria', array('label' => '', 'name' => 'categorias', 'empty' => 'Seleccione una', 'type' => 'select', 'options' => $categorias, 'class' => 'form-control', 'value' => $categoria)); ?>
    </div><br><br>

    <div class="form-group">
        <?php echo $this->Form->submit('Buscar', array('class' => 'btn btn-primary')); ?>
    </div>

    </form><br>





    <legend>
        <h2><b><?php echo __('Productos'); ?></b></h2>
    </legend>

    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col col-lg-2">

                <button type="button" class="btn btn-primary">
                    <?php echo $this->Html->link(__('Nuevo Producto'), array('action' => 'add'), ["style" => "color:white;"]); ?>
                </button>
            </div>
            <div class="col-md-auto">
                <?php echo $this->Form->create('Reporte', array('controller' => 'reportes', 'action' => 'descargarReporteProductos')); ?>
                <fieldset>
                    <?php echo $this->Form->input('codigo', array('type' => 'hidden', 'name' => 'codigo', 'value' => $codigo)) ?>
                    <?php echo $this->Form->input('referencia', array('type' => 'hidden', 'name' => 'referencia', 'value' => $referencia)) ?>
                    <?php echo $this->Form->input('nombre', array('type' => 'hidden', 'name' => 'nombre', 'value' => $nombre)) ?>
                    <?php echo $this->Form->input('categoria', array('type' => 'hidden', 'name' => 'categoria', 'value' => $categoria)) ?>
                    <?php echo $this->Form->submit('Descargar', array('class' => 'btn btn-primary')); ?>
                </fieldset>
                </form><br><br>
                
            </div>
        </div>
    </div>



    <div class="table-responsive">
        <div class="container">
            <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                <tr>
                    <th><?php echo $this->Paginator->sort('Código'); ?></th>
                    <th><?php echo $this->Paginator->sort('Referencia'); ?></th>
                    <th><?php echo $this->Paginator->sort('Nombre'); ?></th>
                    <th><?php echo $this->Paginator->sort('Categoría'); ?></th>
                    <th><?php echo $this->Paginator->sort('Marca'); ?></th>
                    <th><?php echo $this->Paginator->sort('Existencia Mínima'); ?></th>
                    <th><?php echo $this->Paginator->sort('Existencia Máxima'); ?></th>
                    <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?php echo h($producto['Producto']['codigo']); ?>&nbsp;</td>
                    <td><?php echo h($producto['Producto']['referencia']); ?>&nbsp;</td>
                    <td><?php echo h($producto['Producto']['descripcion']); ?>&nbsp;</td>
                    <td>
                        <?php echo h($producto['Categoria']['descripcion']); ?>
                    </td>
                    <td><?php echo h($producto['Producto']['marca']); ?>&nbsp;</td>
                    <td><?php echo h($producto['Producto']['existenciaminima']); ?>&nbsp;</td>
                    <td><?php echo h($producto['Producto']['existenciamaxima']); ?>&nbsp;</td>
                    <td class="actions">
                        <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Producto', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $producto['Producto']['id']))); ?>
                        <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Producto', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $producto['Producto']['id']))); ?>
                        <?php
echo $this->Form->postLink(
    $this->Html->image('png/list-2.png', array('title' => 'Eliminar Producto', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
    array('action' => 'delete', $producto['Producto']['id']), //url
    array('escape' => false), //el escape
    __('Está seguro que desea eliminar el producto %s?', $producto['Producto']['descripcion']) //la confirmacion
);
?>
                    </td>
                </tr>
                <?php endforeach;?>
            </table>
        </div>
    </div>
    <p>
        <?php
echo $this->Paginator->counter(array(
    'format' => __('Página {:page} de {:pages}, mostrando {:current} registro de {:count} en total, iniciando en registro {:start}, finalizando en {:end}'),
));
?>
    </p>
    <div class="pagin">
        <?php echo $this->Paginator->prev('< ' . __('Anterior '), array(), null, array('class' => 'prev disabled')); ?>
        <?php echo $this->Paginator->numbers(array('separator' => ' || ')); ?>
        <?php echo $this->Paginator->next(__(' Siguiente') . ' >', array(), null, array('class' => 'next disabled')); ?>
    </div>
</div>