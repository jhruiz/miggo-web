<?php $this->layout='inicio'; ?>
<div class="gastos view">
<legend><h2><b><?php echo __('Gasto'); ?></b></h2></legend>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '40', 'id' => 'menuvert'))?>
	<dl>
		<dt class="text-info"><?php echo __('Descripcion'); ?></dt>
		<dd>
			<?php echo h($gasto['Gasto']['descripcion']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Usuario'); ?></dt>
		<dd>
			<?php echo $this->Html->link($gasto['Usuario']['nombre'], array('controller' => 'usuarios', 'action' => 'view', $gasto['Usuario']['id'])); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Fecha del Gasto'); ?></dt>
		<dd>
			<?php echo h($gasto['Gasto']['fechagasto']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Fecha del Registro'); ?></dt>
		<dd>
			<?php echo h($gasto['Gasto']['created']); ?>
			&nbsp;
		</dd><br>
                <dt class="text-info"><?php echo __('Item del Gasto')?></dt>
                <dd>
                        <?php echo h(!empty($itemsGasto[$gasto['Gasto']['itemsgasto_id']]) ? $itemsGasto[$gasto['Gasto']['itemsgasto_id']] : "")?>
                </dd><br>                             
		<dt class="text-info"><?php echo __('Valor'); ?></dt>
		<dd>
			<?php echo h("$" . number_format($gasto['Gasto']['valor'],2)); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Cuenta Origen'); ?></dt>
		<dd>
			<?php echo h($gasto['Cuenta']['descripcion']); ?>
			&nbsp;
		</dd><br>
                
		<dt class="text-info"><?php echo __('Tipo'); ?></dt>
		<dd>
			<?php echo h($gasto['Gasto']['traslado'] == '1' ? "Traslado" : "Gasto"); ?>
			&nbsp;
		</dd><br>
                
		<dt class="text-info"><?php echo __('Cuenta Destino'); ?></dt>
		<dd>
			<?php echo h(!empty($gasto['Gasto']['cuentadestino']) ? $listCuentas[$gasto['Gasto']['cuentadestino']] : ""); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Gastos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Gasto'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
