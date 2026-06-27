<?php $this->layout = 'inicio'; ?>

<div class="clientes index container-fluid" style="padding: 20px;">

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h3 class="card-title mb-0"><b><i class="fa fa-search"></i> <?php echo __('Buscar Clientes'); ?></b></h3>
        </div>
        <div class="card-body">
            <?php echo $this->Form->create('Clientes', array('action' => 'search', 'method' => 'post', 'class' => 'form-horizontal')); ?>
            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '14', 'id' => 'menuvert')) ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">NIT / Identificación</label>
                        <?php echo $this->Form->input('nit', array('label' => false, 'name' => 'nit', 'placeholder' => 'Nit del Cliente', 'type' => 'text', 'class' => 'form-control', 'value' => $nit)); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Nombre</label>
                        <?php echo $this->Form->input('nombre', array('label' => false, 'name' => 'nombre', 'placeholder' => 'Nombre del Cliente', 'type' => 'text', 'class' => 'form-control', 'value' => $nombre)); ?>
                    </div>
                </div>
            </div>

            <div class="form-group mb-0">
                <?php echo $this->Form->submit('Buscar Cliente', array('class' => 'btn btn-primary px-4')); ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <?php echo $this->Html->link('<i class="fa fa-user-plus"></i> ' . __('Nuevo Cliente'), array('action' => 'add'), array('class' => 'btn btn-success', 'escape' => false)); ?>
        </div>
        <div class="col-md-6 text-right">
            <?php echo $this->Form->create('Reporte', array('controller' => 'reportes', 'action' => 'descargarReporteClientes', 'class' => 'd-inline-block')); ?>
                <?php echo $this->Form->input('nit', array('type' => 'hidden', 'name' => 'nit', 'value' => $nit)) ?>
                <?php echo $this->Form->input('nombre', array('type' => 'hidden', 'name' => 'nombre', 'value' => $nombre)) ?>
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fa fa-file-excel-o"></i> Descargar Excel
                </button>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h3 class="card-title mb-0"><b><?php echo __('Listado de Clientes'); ?></b></h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover mb-0">
                    <thead>
                        <tr>
                            <th><?php echo $this->Paginator->sort('nit', 'NIT'); ?></th>
                            <th><?php echo $this->Paginator->sort('nombre', 'Nombre'); ?></th>
                            <th><?php echo $this->Paginator->sort('direccion', 'Dirección'); ?></th>
                            <th><?php echo $this->Paginator->sort('telefono', 'Teléfono'); ?></th>
                            <th><?php echo $this->Paginator->sort('ciudade_id', 'Ciudad'); ?></th>
                            <th><?php echo $this->Paginator->sort('celular', 'Celular'); ?></th>
                            <th><?php echo $this->Paginator->sort('limitecredito', 'Límite Crédito'); ?></th>
                            <th><?php echo $this->Paginator->sort('estado_id', 'Estado'); ?></th>
                            <th><?php echo __('Clasificación'); ?></th>
                            <th class="actions text-center"><?php echo __('Acciones'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?php echo h($cliente['Cliente']['nit']); ?></td>
                            <td><b><?php echo h($cliente['Cliente']['nombre']); ?></b></td>
                            <td><?php echo h($cliente['Cliente']['direccion']); ?></td>
                            <td><?php echo h($cliente['Cliente']['telefono']); ?></td>
                            <td><?php echo h($cliente['Ciudadesmiggo']['nombre']); ?></td>
                            <td><?php echo h($cliente['Cliente']['celular']); ?></td>
                            <td><?php echo h(!empty($cliente['Cliente']['limitecredito']) ? '$' . number_format($cliente['Cliente']['limitecredito'], 0) : number_format(0)); ?></td>
                            <td>
                                <span class="badge badge-info">
                                    <?php echo h($cliente['Estado']['descripcion']); ?>
                                </span>
                            </td>
                            <td>
                                <small>
                                    <?php echo h(!empty($cliente['Cliente']['clasificacioncliente_id']) ? $clasificacion[$cliente['Cliente']['clasificacioncliente_id']] : 'S/C'); ?>
                                </small>
                            </td>
                            <td class="actions text-center" style="min-width: 80px;">
                                <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar', 'width' => '20px', 'url' => array('action' => 'edit', $cliente['Cliente']['id']))); ?>
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