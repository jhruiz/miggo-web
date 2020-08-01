<?php $this->layout='inicio'; ?>
<div class="relacionempresas index">
	<legend><h2><b><?php echo __('Empresas Relacionadas'); ?></b></h2></legend>
	<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '37', 'id' => 'menuvert'))?>
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                                <th><?php echo $this->Paginator->sort('nombre'); ?></th>
                                <th><?php echo $this->Paginator->sort('nit'); ?></th>
                                <th><?php echo $this->Paginator->sort('direccion'); ?></th>
                                <th><?php echo $this->Paginator->sort('telefono1', 'Teléfono'); ?></th>
                                <th><?php echo $this->Paginator->sort('email'); ?></th>
                                <th><?php echo $this->Paginator->sort('representantelegal', 'Representante'); ?></th>
                                <th><?php echo $this->Paginator->sort('codigo', 'Código'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($relacionempresas as $relacionempresa): ?>
                <tr>
                        <td><?php echo h($relacionempresa['Relacionempresa']['nombre']); ?>&nbsp;</td>
                        <td><?php echo h($relacionempresa['Relacionempresa']['nit']); ?>&nbsp;</td>
                        <td><?php echo h($relacionempresa['Relacionempresa']['direccion']); ?>&nbsp;</td>
                        <td><?php echo h($relacionempresa['Relacionempresa']['telefono1']); ?>&nbsp;</td>
                        <td><?php echo h($relacionempresa['Relacionempresa']['email']); ?>&nbsp;</td>
                        <td><?php echo h($relacionempresa['Relacionempresa']['representantelegal']); ?>&nbsp;</td>
                        <td><?php echo h($relacionempresa['Relacionempresa']['codigo']); ?>&nbsp;</td>                        
                        <td class="actions">
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Empresa', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $relacionempresa['Relacionempresa']['id']))); ?>
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Empresa', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $relacionempresa['Relacionempresa']['id']))); ?>
                        </td>
                </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Página {:page} de {:pages}, mostrando {:current} registro de {:count} en total, iniciando en registro {:start}, finalizando en {:end}')
	));
	?>	</p>
	<div class="paging">
            <?php echo $this->Paginator->prev('< ' . __('Anterior '), array(), null, array('class' => 'prev disabled')); ?>
            <?php echo $this->Paginator->numbers(array('separator' => ' || ')); ?>
            <?php echo $this->Paginator->next(__(' Siguiente') . ' >', array(), null, array('class' => 'next disabled')); ?>
	</div>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Nueva Empresa Relacionada'), array('action' => 'add')); ?></li>
	</ul>
</div>
