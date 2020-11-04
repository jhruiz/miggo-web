<?php $this->layout = 'inicio';?>
<div class="usuarios index">

    <?php echo $this->Form->create('Usuarios', array('action' => 'search', 'method' => 'post')); ?>
    <legend>
        <h2><b><?php echo __('Buscar Usuarios'); ?></b></h2>
    </legend>
    <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '25', 'id' => 'menuvert')) ?>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group ">
                <?php echo $this->Form->input('nombre', array('label' => 'Nombre', 'placeholder' => 'Nombre del Usuario' ,'name' => 'nombre', 'type' => 'text','class' => 'form-control', 'value' => $nombre)); ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group ">
                    <?php echo $this->Form->input('identificacion', array('label' => 'Identificacion', 'placeholder' => 'Identificación del Usuario' ,'name' => 'identificacion', 'type' => 'text','class' => 'form-control', 'value' => $identificacion)); ?>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group ">
                <?php echo $this->Form->submit('Buscar', array('class' => 'btn btn-primary')); ?>
            </div>
        </div>
        <div class="col-md-9">
            &nbsp;
        </div>
    </div>

    </form><br>

    <legend>
        <h2><b><?php echo __('Usuarios'); ?></b></h2>
    </legend>

    <div class="row justify-content-md-center">
        <div class="col col-lg-2">
            <button type="button" class="btn btn-primary">
                <?php echo $this->Html->link(__('Nuevo Usuario'), array('action' => 'add'), ["style" => "color:white;"]); ?>
            </button> </div>
        <div class="col-md-auto">
            <!-- Inicio zona descargue reporte excel-->
            <?php echo $this->Form->create('Reporte', array('controller' => 'reportes', 'action' => 'descargarReporteUsuarios')); ?>
            <fieldset>
                <?php echo $this->Form->input('nombre', array('type' => 'hidden', 'name' => 'nombre', 'value' => $nombre)) ?>
                <?php echo $this->Form->input('identificacion', array('type' => 'hidden', 'name' => 'identificacion', 'value' => $identificacion)) ?>
                <?php echo $this->Form->submit('Descargar', array('class' => 'btn btn-primary')); ?>
            </fieldset>
            </form><br>
            <!-- Fin zona descargue reporte excel -->
        </div>
    </div>


    <div class="table-responsive">
        <div class="container">
            <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                <tr>
                    <th><?php echo $this->Paginator->sort('nombre'); ?></th>
                    <th><?php echo $this->Paginator->sort('identificacion'); ?></th>
                    <th><?php echo $this->Paginator->sort('username'); ?></th>
                    <th><?php echo $this->Paginator->sort('perfil'); ?></th>
                    <th><?php echo $this->Paginator->sort('estado_id'); ?></th>
                    <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo h($usuario['Usuario']['nombre']); ?>&nbsp;</td>
                    <td><?php echo h($usuario['Usuario']['identificacion']); ?>&nbsp;</td>
                    <td><?php echo h($usuario['Usuario']['username']); ?>&nbsp;</td>
                    <td>
                        <?php echo $this->Html->link($usuario['Perfile']['descripcion'], array('controller' => 'perfiles', 'action' => 'view', $usuario['Perfile']['id'])); ?>
                    </td>
                    <td>
                        <?php echo $usuario['Estado']['descripcion']; ?>
                    </td>
                    <td class="actions">
                        <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Usuario', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $usuario['Usuario']['id']))); ?>
                        <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Usuario', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $usuario['Usuario']['id']))); ?>
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
?> </p>
    <div class="paging">
        <?php echo $this->Paginator->prev('< ' . __('Anterior '), array(), null, array('class' => 'prev disabled')); ?>
        <?php echo $this->Paginator->numbers(array('separator' => ' || ')); ?>
        <?php echo $this->Paginator->next(__(' Siguiente') . ' >', array(), null, array('class' => 'next disabled')); ?>

    </div>
</div>