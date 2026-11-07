<?php $this->layout = 'inicio'; ?>

<div class="centroscostos index container-fluid p-4">

    <div class="miggo-index-container shadow-sm">

        <!-- CABECERA DE PÁGINA Y BOTÓN NUEVO -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="miggo-title-header">
                <i class="fa fa-cubes"></i> Centros de Costo
            </div>
            <div>
                <?php echo $this->Html->link(
                    '<i class="fa fa-plus"></i> Nuevo Centro de Costo',
                    array('action' => 'add'),
                    array('class' => 'btn btn-miggo-success', 'escape' => false)
                ); ?>
            </div>
        </div>

        <!-- FILTRO DE BÚSQUEDA RAPIDA -->
        <div class="miggo-search-box">
            <?php echo $this->Form->create('Centrocosto', array('action' => 'search', 'method' => 'post', 'class' => 'm-0')); ?>
            <div class="row align-items-end">
                <div class="col-md-9 mb-2 mb-md-0">
                    <label class="miggo-label">BUSCAR POR NOMBRE</label>
                    <?php echo $this->Form->input('nombre', array(
                        'label'       => false,
                        'class'       => 'form-control miggo-input',
                        'placeholder' => 'Escriba el nombre del centro de costo a buscar...',
                        'value'       => $filtNombre,
                        'div'         => false
                    )); ?>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-miggo-primary w-100">
                        <i class="fa fa-search"></i> BUSCAR
                    </button>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>

        <!-- TABLA DE RESULTADOS -->
        <div class="table-responsive">
            <table class="table miggo-table">
                <thead>
                    <tr>
                        <th style="width: 60%;">NOMBRE</th>
                        <th class="text-center" style="width: 20%;">EDITABLE</th>
                        <th class="text-center" style="width: 20%;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($centros)): ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-5">
                            <i class="fa fa-inbox fa-3x mb-3 text-light" style="color: #cbd5e0 !important;"></i><br>
                            <span class="font-weight-bold" style="font-size: 14px;">No se encontraron centros de costo registrados.</span>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($centros as $centro): ?>
                    <tr id="fila-<?php echo h($centro['Centroscosto']['id']); ?>">
                        <td>
                            <strong style="color: #2d3748; font-size: 13.5px;">
                                <?php echo h($centro['Centroscosto']['nombre']); ?>
                            </strong>
                        </td>
                        <td class="text-center">
                            <?php if ($centro['Centroscosto']['editable'] == '1'): ?>
                                <span class="badge-miggo-success"><i class="fa fa-check"></i> SÍ</span>
                            <?php else: ?>
                                <span class="badge-miggo-secondary"><i class="fa fa-lock"></i> NO</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <!-- EDITAR -->
                            <?php echo $this->Html->link(
                                '<i class="fa fa-pencil action-icon"></i>',
                                array('action' => 'edit', $centro['Centroscosto']['id']),
                                array('escape' => false, 'class' => 'text-primary mr-2', 'title' => 'Editar Centro de Costo')
                            ); ?>

                            <!-- ELIMINAR -->
                            <?php if ($centro['Centroscosto']['editable'] == '1'): ?>
                                <a href="javascript:void(0);"
                                   onclick="eliminarCentroCosto(<?php echo $centro['Centroscosto']['id']; ?>, '<?php echo h(addslashes($centro['Centroscosto']['nombre'])); ?>')"
                                   class="text-danger" title="Eliminar Centro de Costo">
                                    <i class="fa fa-trash-o action-icon"></i>
                                </a>
                            <?php else: ?>
                                <i class="fa fa-lock text-muted action-icon" style="opacity: 0.5;" title="Registro bloqueado de fábrica"></i>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>