<?php $this->layout = 'inicio'; ?>

<div class="centroscostos form container-fluid" style="padding: 20px;">
    <?php echo $this->Form->create('Centrocosto', array('type' => 'post', 'class' => 'form-horizontal')); ?>
    <?php echo $this->Form->input('id', array('type' => 'hidden')); ?>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h3 class="card-title mb-0"><b><i class="fa fa-pencil"></i> Editar Centro de Costo</b></h3>
        </div>
        <div class="card-body bg-light-gray">

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
                        <?php echo $this->Form->input('nombre', array(
                            'label'       => false,
                            'class'       => 'form-control',
                            'placeholder' => 'Nombre del centro de costo',
                        )); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Editable</label>
                        <div style="padding-top:8px;">
                            <label class="checkbox-inline">
                                <?php echo $this->Form->input('editable', array(
                                    'type'    => 'checkbox',
                                    'label'   => false,
                                    'checked' => (isset($this->request->data['Centrocosto']['editable']) && $this->request->data['Centrocosto']['editable'] == '1'),
                                )); ?>
                                Permitir modificar/eliminar
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Usuarios Asignados</label>
                        <?php echo $this->Form->input('usuarios', array(
                            'label'    => false,
                            'type'     => 'select',
                            'multiple' => true,
                            'class'    => 'form-control select2',
                            'options'  => $usuarios,
                            'selected' => isset($this->request->data['Centrocosto']['usuarios']) ? $this->request->data['Centrocosto']['usuarios'] : array(),
                        )); ?>
                        <small class="text-muted">Un usuario puede pertenecer a varios centros de costo.</small>
                    </div>
                </div>
            </div>

            <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId)); ?>

            <hr>
            <div class="row">
                <div class="col-md-12">
                    <?php echo $this->Form->submit('Actualizar Centro de Costo', array('class' => 'btn btn-primary px-4')); ?>
                    &nbsp;
                    <?php echo $this->Html->link('Cancelar', array('action' => 'index'), array('class' => 'btn btn-default')); ?>
                </div>
            </div>
        </div>
    </div>

    <?php echo $this->Form->end(); ?>
</div>

<script>
$(document).ready(function() {
    $('.select2').select2({ placeholder: 'Seleccione usuarios...', width: '100%' });
});
</script>
