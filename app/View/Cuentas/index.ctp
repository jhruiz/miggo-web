<?php $this->layout='inicio'; ?>
<div class="cuentas index">
	<legend><h2><b><?php echo __('Cuentas'); ?></b></h2></legend> 
	<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '39', 'id' => 'menuvert'))?>
        <div class="table-responsive">
            <div class="container-fluid">         
                <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                <tr>
                                <th><?php echo $this->Paginator->sort('descripcion', 'Nombre Cuenta'); ?></th>
                                <th><?php echo $this->Paginator->sort('numerocuenta', 'Número Cuenta'); ?></th>
                                <th><?php echo $this->Paginator->sort('saldo'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php $sum = 0; ?>
                <?php foreach ($cuentas as $cuenta): ?>
                <?php $sum += $cuenta['Cuenta']['saldo']; ?>
                <tr>
                        <td><?php echo h($cuenta['Cuenta']['descripcion']); ?>&nbsp;</td>
                        <td><?php echo h($cuenta['Cuenta']['numerocuenta']); ?>&nbsp;</td>
                        <td><?php echo h("$" . number_format($cuenta['Cuenta']['saldo'],2)); ?>&nbsp;</td>                        
                        <td class="actions">                            
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Cuenta', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $cuenta['Cuenta']['id']))); ?>
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Cuenta', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $cuenta['Cuenta']['id']))); ?>
                        </td>
                </tr>
        <?php endforeach; ?>
                <tr><td colspan="2"><b>TOTAL</b></td><td><b><?php echo '$' . number_format($sum,2); ?></b></td><td>&nbsp;</td></tr>
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
	<?php
		echo $this->Paginator->prev('< ' . __('Anterior '), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ' || '));
		echo $this->Paginator->next(__(' Siguiente') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Nueva Cuenta'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Gastos'), array('controller' => 'gastos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Gasto'), array('controller' => 'gastos', 'action' => 'add')); ?> </li>
	</ul>
</div>
