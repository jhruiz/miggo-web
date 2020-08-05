<?php $this->layout = 'inicio';?>
<div class="notafacturas index">
	<legend><h2><b><?php echo __('Notas Facturas'); ?></b></h2></legend>
	 <!--Enlaces de acción -->
	 <div class="actions">
        <button type="button" class="btn btn-primary">
		 	<?php echo $this->Html->link(__('Nueva Nota Factura'), array('action' => 'add'), ["style" => "color:white;"]); ?>
        </button>
	</div>

	<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '36', 'id' => 'menuvert')) ?>
	<table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
	<tr>
			<th><?php echo $this->Paginator->sort('descripcion', 'Nota'); ?></th>
			<th class="actions"><?php echo __('Acciones'); ?></th>
	</tr>
	<?php foreach ($notafacturas as $notafactura): ?>
	<tr>
		<td><?php echo h($notafactura['Notafactura']['descripcion']); ?>&nbsp;</td>
                        <td class="actions">
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Nota', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $notafactura['Notafactura']['id']))); ?>
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Nota', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $notafactura['Notafactura']['id']))); ?>
                        </td>
	</tr>
<?php endforeach;?>
	</table>
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
