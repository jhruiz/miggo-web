<?php $this->layout = 'inicio'; ?>

<div class="retenciones form container-fluid" style="padding: 20px;">
    <?php echo $this->Form->create('Retencione', array('type' => 'post', 'class' => 'form-horizontal')); ?>
    <?php echo $this->Form->input('id', array('type' => 'hidden')); ?>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h3 class="card-title mb-0"><b><i class="fa fa-pencil"></i> Editar Retención</b></h3>
        </div>
        <div class="card-body bg-light-gray">
            <?php echo $this->element('Retenciones/form_fields'); ?>

            <hr>
            <div class="row">
                <div class="col-md-12">
                    <?php echo $this->Form->submit('Actualizar Retención', array('class' => 'btn btn-primary px-4')); ?>
                    &nbsp;
                    <?php echo $this->Html->link('Cancelar', array('action' => 'index'), array('class' => 'btn btn-default')); ?>
                </div>
            </div>
        </div>
    </div>

    <?php echo $this->Form->end(); ?>
</div>
