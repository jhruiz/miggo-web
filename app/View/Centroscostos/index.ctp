<?php $this->layout = 'inicio'; ?>

<div class="centroscostos index container-fluid" style="padding: 20px;">

    <!-- ── FILTROS ── -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h3 class="card-title mb-0"><b><i class="fa fa-search"></i> Buscar Centros de Costo</b></h3>
        </div>
        <div class="card-body">
            <?php echo $this->Form->create('Centrocosto', array('action' => 'search', 'method' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Nombre</label>
                        <?php echo $this->Form->input('nombre', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Nombre del centro de costo', 'value' => $filtNombre)); ?>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-group mb-3 w-100">
                        <?php echo $this->Form->submit('Buscar', array('class' => 'btn btn-primary btn-block')); ?>
                    </div>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

    <!-- ── ACCIONES ── -->
    <div class="row mb-3">
        <div class="col-md-12">
            <?php echo $this->Html->link(
                '<i class="fa fa-plus"></i> Nuevo Centro de Costo',
                array('action' => 'add'),
                array('class' => 'btn btn-success', 'escape' => false)
            ); ?>
        </div>
    </div>

    <!-- ── TABLA ── -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h3 class="card-title mb-0"><b>Listado de Centros de Costo</b></h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Usuarios Asignados</th>
                            <th class="text-center">Editable</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($centros)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted" style="padding:30px;">
                                <i class="fa fa-inbox fa-2x"></i><br>
                                No se encontraron centros de costo.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($centros as $centro): ?>
                        <tr id="fila-<?php echo h($centro['Centrocosto']['id']); ?>">
                            <td><b><?php echo h($centro['Centrocosto']['nombre']); ?></b></td>
                            <td>
                                <?php if (!empty($centro['Usuario'])): ?>
                                    <?php foreach ($centro['Usuario'] as $u): ?>
                                        <span class="badge badge-info" style="margin:2px;"><?php echo h($u['nombre']); ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <small class="text-muted">Sin usuarios asignados</small>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($centro['Centrocosto']['editable'] == '1'): ?>
                                    <span class="badge badge-success">Sí</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary" style="background:#95a5a6;">No</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center" style="min-width:90px;">
                                <?php echo $this->Html->link(
                                    '<i class="fa fa-pencil"></i>',
                                    array('action' => 'edit', $centro['Centrocosto']['id']),
                                    array('escape' => false, 'class' => 'text-primary', 'style' => 'margin-right:8px;', 'title' => 'Editar')
                                ); ?>
                                <?php if ($centro['Centrocosto']['editable'] == '1'): ?>
                                <a href="javascript:void(0);"
                                   onclick="eliminarCentroCosto(<?php echo $centro['Centrocosto']['id']; ?>, '<?php echo h(addslashes($centro['Centrocosto']['nombre'])); ?>')"
                                   class="text-danger" title="Eliminar">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                                <?php else: ?>
                                <i class="fa fa-lock text-muted" title="No editable"></i>
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
</div>

<input type="hidden" id="baseUrl" value="<?php echo $this->request->base; ?>">

<script>
function eliminarCentroCosto(id, nombre) {
    bootbox.confirm({
        message: '¿Está seguro que desea eliminar el centro de costo <b>' + nombre + '</b>?',
        buttons: {
            confirm: { label: 'Eliminar', className: 'btn-danger' },
            cancel:  { label: 'Cancelar', className: 'btn-default' }
        },
        callback: function(result) {
            if (!result) return;
            $.ajax({
                type: 'POST',
                url:  $('#baseUrl').val() + '/centroscostos/delete',
                data: { id: id },
                success: function(data) {
                    var resp = JSON.parse(data);
                    if (resp.resp) {
                        $('#fila-' + id).fadeOut(300, function() { $(this).remove(); });
                    } else {
                        bootbox.alert(resp.msg);
                    }
                }
            });
        }
    });
}
</script>
