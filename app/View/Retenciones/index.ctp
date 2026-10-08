<?php $this->layout = 'inicio'; ?>

<div class="retenciones index container-fluid" style="padding: 20px;">

    <!-- ── FILTROS ── -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h3 class="card-title mb-0"><b><i class="fa fa-search"></i> Buscar Retenciones</b></h3>
        </div>
        <div class="card-body">
            <?php echo $this->Form->create('Retencione', array('action' => 'search', 'method' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Nombre</label>
                        <?php echo $this->Form->input('nombre', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Nombre de la retención', 'value' => $filtNombre)); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Estado</label>
                        <?php echo $this->Form->input('activo', array(
                            'label'   => false,
                            'type'    => 'select',
                            'class'   => 'form-control',
                            'empty'   => 'Todos',
                            'options' => array('1' => 'Activa', '0' => 'Inactiva'),
                            'value'   => $filtActivo,
                        )); ?>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
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
                '<i class="fa fa-plus"></i> Nueva Retención',
                array('action' => 'add'),
                array('class' => 'btn btn-success', 'escape' => false)
            ); ?>
        </div>
    </div>

    <!-- ── TABLA ── -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h3 class="card-title mb-0"><b>Listado de Retenciones</b></h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th class="text-right">Porcentaje</th>
                            <th>Categoría</th>
                            <th>Tipo Tarifa</th>
                            <th>Cuenta Venta</th>
                            <th>Cuenta Compra</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($retenciones)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted" style="padding:30px;">
                                <i class="fa fa-inbox fa-2x"></i><br>
                                No se encontraron retenciones.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($retenciones as $ret): ?>
                        <tr id="fila-<?php echo h($ret['Retencione']['id']); ?>">
                            <td><b><?php echo h($ret['Retencione']['nombre']); ?></b></td>
                            <td class="text-right"><?php echo h(number_format($ret['Retencione']['porcentaje'], 3)); ?>%</td>
                            <td><?php echo isset($categorias[$ret['Retencione']['categoriaretencion_id']]) ? h($categorias[$ret['Retencione']['categoriaretencion_id']]) : '—'; ?></td>
                            <td><?php echo isset($tiposTarifa[$ret['Retencione']['tipotarifa_id']]) ? h($tiposTarifa[$ret['Retencione']['tipotarifa_id']]) : '—'; ?></td>
                            <td><small><?php echo h(!empty($ret['CV']['descripcion']) ? $ret['CV']['descripcion'] : '—'); ?></small></td>
                            <td><small><?php echo h(!empty($ret['CC']['descripcion']) ? $ret['CC']['descripcion'] : '—'); ?></small></td>
                            <td>
                                <?php if ($ret['Retencione']['activo'] == '1'): ?>
                                    <span class="badge badge-success">Activa</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary" style="background:#95a5a6;">Inactiva</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center" style="min-width:90px;">
                                <?php echo $this->Html->link(
                                    '<i class="fa fa-pencil"></i>',
                                    array('action' => 'edit', $ret['Retencione']['id']),
                                    array('escape' => false, 'class' => 'text-primary', 'style' => 'margin-right:8px;', 'title' => 'Editar')
                                ); ?>
                                <a href="javascript:void(0);"
                                   onclick="eliminarRetencion(<?php echo $ret['Retencione']['id']; ?>, '<?php echo h(addslashes($ret['Retencione']['nombre'])); ?>')"
                                   class="text-danger" title="Eliminar">
                                    <i class="fa fa-trash-o"></i>
                                </a>
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
function eliminarRetencion(id, nombre) {
    bootbox.confirm({
        message: '¿Está seguro que desea eliminar la retención <b>' + nombre + '</b>?',
        buttons: {
            confirm: { label: 'Eliminar', className: 'btn-danger' },
            cancel:  { label: 'Cancelar', className: 'btn-default' }
        },
        callback: function(result) {
            if (!result) return;
            $.ajax({
                type: 'POST',
                url:  $('#baseUrl').val() + '/retenciones/delete',
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
