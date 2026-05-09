<?php $this->layout = 'inicio'; ?>
<?php echo ($this->Html->script('ubicacion/obtenerubicacion')); ?>

<div class="proveedores index container-fluid" style="padding: 20px;">

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h3 class="card-title mb-0"><b><i class="fa fa-search"></i> <?php echo __('Buscar Proveedores'); ?></b></h3>
        </div>
        <div class="card-body">
            <?php echo $this->Form->create('Proveedores', array('action' => 'search', 'method' => 'post', 'class' => 'form-horizontal')); ?>
            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '27', 'id' => 'menuvert')) ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Nombre</label>
                        <input name="nombre" id="nombre" class="form-control" placeholder="Nombre Proveedor" value="<?php echo h($nombre); ?>" type="text">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">NIT</label>
                        <input name="nit" id="nit" class="form-control" placeholder="NIT Proveedor" type="text" value="<?php echo h($nit); ?>">
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">País</label>
                        <?php echo $this->Form->input('pais', array('label' => false, 'name' => 'pais', 'empty' => 'Seleccione país', 'type' => 'select', 'options' => $paises, 'class' => 'form-control select2 selectPais', 'onchange' => 'obtenerDptos();')); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Departamento</label>
                        <?php echo $this->Form->input('departamento', array('label' => false, 'name' => 'departamento', 'empty' => 'Seleccione Departamento', 'type' => 'select', 'class' => 'form-control select2 selectDpto', 'onchange' => 'obtenerCiudades();')); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Ciudad</label>
                        <?php echo $this->Form->input('ciudad', array('label' => false, 'name' => 'ciudad', 'empty' => 'Seleccione Ciudad', 'type' => 'select', 'class' => 'form-control select2 selectCiudad')); ?>
                    </div>
                </div>
            </div>

            <div class="form-group mb-0">
                <?php echo $this->Form->submit('Buscar Proveedor', array('class' => 'btn btn-primary')); ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <?php echo $this->Html->link('<i class="fa fa-plus"></i> ' . __('Nuevo Proveedor'), array('action' => 'add'), array('class' => 'btn btn-success', 'escape' => false)); ?>
        </div>
        <div class="col-md-6 text-right">
            <?php echo $this->Form->create('Reporte', array('controller' => 'reportes', 'action' => 'descargarReporteProveedores', 'class' => 'd-inline-block')); ?>
                <?php echo $this->Form->input('nombre', array('type' => 'hidden', 'name' => 'nombre', 'value' => $nombre)) ?>
                <?php echo $this->Form->input('nit', array('type' => 'hidden', 'name' => 'nit', 'value' => $nit)) ?>
                <?php echo $this->Form->input('ciudad', array('type' => 'hidden', 'name' => 'ciudad', 'value' => $ciudad)) ?>
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fa fa-file-excel-o"></i> Descargar Excel
                </button>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h3 class="card-title mb-0"><b><?php echo __('Listado de Proveedores'); ?></b></h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover mb-0">
                    <thead>
                        <tr>
                            <th><?php echo $this->Paginator->sort('nit', 'NIT'); ?></th>
                            <th><?php echo $this->Paginator->sort('nombre', 'Nombre'); ?></th>
                            <th><?php echo h('Código'); ?></th>
                            <th><?php echo $this->Paginator->sort('direccion', 'Dirección'); ?></th>
                            <th><?php echo $this->Paginator->sort('telefono', 'Teléfono'); ?></th>
                            <th><?php echo $this->Paginator->sort('ciudade_id', 'Ciudad'); ?></th>
                            <th><?php echo $this->Paginator->sort('celular', 'Celular'); ?></th>
                            <th><?php echo $this->Paginator->sort('estado_id', 'Estado'); ?></th>
                            <th><?php echo __('Régimen'); ?></th>
                            <th class="actions text-center"><?php echo __('Acciones'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($proveedores as $proveedore): ?>
                        <tr>
                            <td><?php echo h($proveedore['Proveedore']['nit']); ?></td>
                            <td><b><?php echo h($proveedore['Proveedore']['nombre']); ?></b></td>
                            <td><small><?php echo h($proveedore['Proveedore']['empresa_id'] . $proveedore['Proveedore']['usuario_id'] . '-' . $proveedore['Proveedore']['id']); ?></small></td>
                            <td><?php echo h($proveedore['Proveedore']['direccion']); ?></td>
                            <td><?php echo h($proveedore['Proveedore']['telefono']); ?></td>
                            <td><?php echo h($proveedore['Ciudadesmiggo']['nombre']); ?></td>
                            <td><?php echo h($proveedore['Proveedore']['celular']); ?></td>
                            <td><?php echo h($proveedore['Estado']['descripcion']); ?></td>
                            <td><?php echo ($regimen[$proveedore['Proveedore']['regimene_id']]); ?></td>
                            <td class="actions text-center" style="min-width: 100px;">
                                <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar', 'width' => '20px', 'url' => array('action' => 'edit', $proveedore['Proveedore']['id']))); ?>
                                <?php 
                                    echo $this->Form->postLink(
                                        $this->Html->image('png/list-2.png', array('title' => 'Eliminar', 'width' => '20px')),
                                        array('action' => 'delete', $proveedore['Proveedore']['id']),
                                        array('escape' => false),
                                        __('¿Está seguro que desea eliminar el proveedor %s?', $proveedore['Proveedore']['nombre'])
                                    ); 
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <small class="text-muted">
                        <?php echo $this->Paginator->counter(array('format' => __('Página {:page} de {:pages}, mostrando {:current} registros'))); ?>
                    </small>
                </div>
                <div class="col-md-6 text-right">
                    <div class="paging">
                        <?php
                            echo $this->Paginator->prev('< ' . __('Anterior'), array('class' => 'btn btn-xs btn-outline-secondary'), null, array('class' => 'prev disabled btn btn-xs btn-outline-secondary'));
                            echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'btn btn-xs btn-outline-secondary'));
                            echo $this->Paginator->next(__('Siguiente') . ' >', array('class' => 'btn btn-xs btn-outline-secondary'), null, array('class' => 'next disabled btn btn-xs btn-outline-secondary'));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>