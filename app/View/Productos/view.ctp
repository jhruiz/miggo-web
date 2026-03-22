<?php $this->layout = 'inicio'; ?>
<?php echo ($this->Html->css('productos/verproducto.css')); ?>
<div class="productos view container-fluid" id="fichaView" style="padding:20px;">
    
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-12">
            <h2><b>Ficha Técnica del Producto</b></h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="panel panel-default panel-moderno">
                <div class="panel-body text-center" style="padding:20px;">
                    <?php if (!empty($producto['Imagenesitem'])): ?>
                        <div class="row">
                            <?php foreach ($producto['Imagenesitem'] as $img): ?>
                                <div class="col-xs-3 col-sm-3 col-md-3">
                                    <img src="<?php echo $this->webroot . "img/productos/{$empresaId}/" . $img['Imagenesitem']['url']; ?>" 
                                         class="img-thumbnail thumb-pildora" 
                                         onclick="document.getElementById('mainViewImage').src = this.src">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="panel panel-default panel-moderno" style="padding: 25px;">
                <div class="panel-body" style="padding: 0;">
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-12">
                            <h1><?php echo h($producto['Producto']['descripcion']); ?></h1>
                            <p class="text-muted">Ref: <?php echo h($producto['Producto']['referencia']); ?> | Código: <?php echo h($producto['Producto']['codigo']); ?></p>
                        </div>
                    </div>

                    <div class="info-tec row" style="margin-bottom: 25px;">
                        <div class="col-sm-6">
                            <label>Categoría</label>
                            <p><?php echo h($producto['Categoria']['descripcion']); ?></p>
                        </div>
                        <div class="col-sm-6">
                            <label>Marca</label>
                            <p><?php echo h($producto['Producto']['marca'] ?: 'N/A'); ?></p>
                        </div>
                    </div>

                    <div class="info-tec row text-center" style="margin-bottom: 25px; background: #fafafa; padding: 15px; border-radius: 8px;">
                        <div class="col-sm-4">
                            <label>Stock Mínimo</label><br>
                            <span class="stock-badge label label-danger"><?php echo h($producto['Producto']['existenciaminima']); ?> uds</span>
                        </div>
                        <div class="col-sm-4">
                            <label>Stock Máximo</label><br>
                            <span class="stock-badge label label-primary"><?php echo h($producto['Producto']['existenciamaxima']); ?> uds</span>
                        </div>
                        <div class="col-sm-4">
                            <label>Marketplace</label><br>
                            <?php if ($producto['Producto']['mostrarencatalogo'] == '1'): ?>
                                <span class="stock-badge label label-success">ACTIVO</span>
                            <?php else: ?>
                                <span class="stock-badge label label-default">INACTIVO</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="text-info"><b><i class="fa fa-tags"></i> Palabras Clave</b></label>
                            <div style="margin-top:10px;">
                                <?php if (!empty($producto['Palabrasclave'])): ?>
                                    <?php foreach ($producto['Palabrasclave'] as $pc): ?>
                                        <span class="tag-pildora"><?php echo h($pc['Palabrasclave']['palabra']); ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <small class="text-muted">Sin palabras clave.</small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top:20px;">
        <div class="col-md-12">
            <div class="panel panel-default panel-moderno">
                <div class="panel-heading" style="background:#fff; padding: 15px 20px;">
                    <b><i class="fa fa-align-left"></i> Descripción Detallada</b>
                </div>
                <div class="panel-body render-summernote">
                    <?php echo $producto['Producto']['desc_extensa']; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top:10px; margin-bottom: 30px;">
        <div class="col-md-12 text-right">
            <?php echo $this->Html->link('<i class="fa fa-edit"></i> Editar', array('action' => 'edit', $producto['Producto']['id']), array('class' => 'btn btn-warning btn-lg px-5', 'escape' => false)); ?>
            <?php echo $this->Html->link('Volver al Listado', array('action' => 'index'), array('class' => 'btn btn-default btn-lg')); ?>
        </div>
    </div>
</div>