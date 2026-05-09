<?php $this->layout = 'inicio'; ?>
<?php echo ($this->Html->script('ubicacion/obtenerubicacion')); ?>

<div class="depositos index container-fluid" style="padding: 20px;">

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h3 class="card-title mb-0"><b><i class="fa fa-search"></i> <?php echo __('Buscar Bodegas'); ?></b></h3>
        </div>
        <div class="card-body">
            <?php echo $this->Form->create('Depositos', array('action' => 'search', 'method' => 'post', 'class' => 'form-horizontal')); ?>
            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '15', 'id' => 'menuvert')) ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Nombre</label>
                        <?php echo $this->Form->input('nombre', array('label' => false, 'name' => 'nombre', 'placeholder' => 'Nombre de la Bodega', 'type' => 'text', 'class' => 'form-control', 'value' => $nombre)); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Encargado</label>
                        <?php echo $this->Form->input('encargado', array('label' => false, 'name' => 'encargado', 'empty' => 'Seleccione uno', 'type' => 'select', 'options' => $usuarios, 'class' => 'form-control', 'value' => $encargado)); ?>
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
                <?php echo $this->Form->submit('Buscar', array('class' => 'btn btn-primary')); ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <?php echo $this->Html->link(__('Nueva Bodega'), array('action' => 'add'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="col-md-6 text-right">
            <?php echo $this->Form->create('Reporte', array('controller' => 'reportes', 'action' => 'descargarReporteDepositos', 'class' => 'form-inline d-inline-block')); ?>
                <?php echo $this->Form->input('nombre', array('type' => 'hidden', 'name' => 'nombre', 'value' => $nombre)) ?>
                <?php echo $this->Form->input('ciudad', array('type' => 'hidden', 'name' => 'ciudad', 'value' => $ciudad)) ?>
                <?php echo $this->Form->input('encargado', array('type' => 'hidden', 'name' => 'encargado', 'value' => $encargado)) ?>
                <?php echo $this->Form->submit('Descargar Excel', array('class' => 'btn btn-outline-primary')); ?>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h3 class="card-title mb-0"><b><?php echo __('Bodegas'); ?></b></h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover mb-0">
                    <thead>
                        <tr>
                            <th><?php echo $this->Paginator->sort('descripcion', 'Nombre'); ?></th>
                            <th><?php echo $this->Paginator->sort('ciudade_id', 'Ciudad'); ?></th>
                            <th><?php echo $this->Paginator->sort('telefono', 'Teléfono'); ?></th>
                            <th><?php echo $this->Paginator->sort('direccion', 'Dirección'); ?></th>
                            <th><?php echo $this->Paginator->sort('usuario_id', 'Encargado'); ?></th>
                            <th><?php echo ('Código'); ?></th>
                            <th class="actions text-center"><?php echo __('Acciones'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($depositos as $deposito): ?>
                        <tr>
                            <td><?php echo h($deposito['Deposito']['descripcion']); ?></td>
                            <td><?php echo h($deposito['Ciudadesmiggo']['nombre']); ?></td>
                            <td><?php echo h($deposito['Deposito']['telefono']); ?></td>
                            <td><?php echo h($deposito['Deposito']['direccion']); ?></td>
                            <td><?php echo h($deposito['Usuario']['nombre']); ?></td>
                            <td><?php echo h($deposito['Deposito']['empresa_id'] . $deposito['Deposito']['ciudade_id'] . '-' . $deposito['Deposito']['id']) ?></td>
                            <td class="actions text-center">
                                <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar', 'width' => '20px', 'url' => array('action' => 'edit', $deposito['Deposito']['id']))); ?>
                                <?php echo $this->Form->postLink($this->Html->image('png/list-2.png', array('title' => 'Eliminar', 'width' => '20px')), array('action' => 'delete', $deposito['Deposito']['id']), array('escape' => false), __('¿Está seguro que desea eliminar la Bodega %s?', $deposito['Deposito']['descripcion'])); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">
                        <small><?php echo $this->Paginator->counter(array('format' => __('Página {:page} de {:pages}, mostrando {:current} registro de {:count}'))); ?></small>
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="paging">
                        <?php
                            echo $this->Paginator->prev('< ' . __('Anterior '), array('class' => 'btn btn-sm btn-light'), null, array('class' => 'prev disabled btn btn-sm btn-light'));
                            echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'btn btn-sm btn-light'));
                            echo $this->Paginator->next(__(' Siguiente') . ' >', array('class' => 'btn btn-sm btn-light'), null, array('class' => 'next disabled btn btn-sm btn-light'));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>