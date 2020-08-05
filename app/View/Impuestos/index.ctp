<?php $this->layout = 'inicio';?>
<div class="impuestos index">
    <legend><h2><b><?php echo __('Impuestos'); ?></b></h2></legend>
    <!--Enlaces de acción -->
		<div class="actions">
            <button type="button" class="btn btn-primary">
            <?php echo $this->Html->link(__('Nuevo Impuesto'), array('action' => 'add'), ["style" => "color:white;"]); ?>
            </button>
        </div>
	<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '20', 'id' => 'menuvert')) ?>
        <div class="table-responsive">
            <div class="container">
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                                <th><?php echo $this->Paginator->sort('descripcion', 'Nombre del Impuesto'); ?></th>
                                <th><?php echo $this->Paginator->sort('valor', 'Valor del Impuesto'); ?></th>
                                <th><?php echo ('Código'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($impuestos as $impuesto): ?>
                <tr>
                        <td><?php echo h($impuesto['Impuesto']['descripcion']); ?>&nbsp;</td>
                        <td><?php echo h($impuesto['Impuesto']['valor']); ?>&nbsp;</td>
                        <td><?php echo h($impuesto['Impuesto']['empresa_id'] . '-' . $impuesto['Impuesto']['id']); ?>&nbsp;</td>
                        <td class="actions">
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Impuesto', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $impuesto['Impuesto']['id']))); ?>
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Impuesto', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $impuesto['Impuesto']['id']))); ?>
                            <?php
echo $this->Form->postLink(
    $this->Html->image('png/list-2.png', array('title' => 'Eliminar Impuesto', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
    array('action' => 'delete', $impuesto['Impuesto']['id']), //url
    array('escape' => false), //el escape
    __('Está seguro que desea eliminar el impuesto %s?', $impuesto['Impuesto']['descripcion']) //la confirmacion
);
?>
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
?>	</p>
	<div class="paging">
	<?php
echo $this->Paginator->prev('< ' . __('Anterior '), array(), null, array('class' => 'prev disabled'));
echo $this->Paginator->numbers(array('separator' => ' || '));
echo $this->Paginator->next(__(' Siguiente') . ' >', array(), null, array('class' => 'next disabled'));
?>
	</div>
</div>