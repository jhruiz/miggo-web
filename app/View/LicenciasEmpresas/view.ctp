<?php $this->layout='inicio'; ?>
<div class="licenciasEmpresas view">
<legend><h2><b><?php echo __('Licencia - Empresa'); ?></b></h2></legend>
	<dl>
		<dt class="text-info"><?php echo __('Inicio de Licencia'); ?></dt>
		<dd>
			<?php echo h($licenciasEmpresa['LicenciasEmpresa']['fechainicio']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Fin de Licencia'); ?></dt>
		<dd>
			<?php echo h($licenciasEmpresa['LicenciasEmpresa']['fechafin']); ?>
			&nbsp;
                </dd><br>
		<dt class="text-info"><?php echo __('Días Licencia'); ?></dt>
		<dd>
			<?php echo $this->Html->link($licenciasEmpresa['Licencia']['cantidaddias'], array('controller' => 'licencias', 'action' => 'view', $licenciasEmpresa['Licencia']['id'])); ?>
			&nbsp;
                </dd><br>
		<dt class="text-info"><?php echo __('Empresa'); ?></dt>
		<dd>
			<?php echo $this->Html->link($licenciasEmpresa['Empresa']['nombre'], array('controller' => 'empresas', 'action' => 'view', $licenciasEmpresa['Empresa']['id'])); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo $this->Html->link($licenciasEmpresa['Estado']['descripcion'], array('controller' => 'estados', 'action' => 'view', $licenciasEmpresa['Estado']['id'])); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Código Licencia'); ?></dt>
		<dd>
			<?php echo h($licenciasEmpresa['LicenciasEmpresa']['codigo']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Licencia - Empresa'), array('action' => 'edit', $licenciasEmpresa['LicenciasEmpresa']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Licencias - Empresas'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Licencia - Empresa'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Licencias'), array('controller' => 'licencias', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Licencia'), array('controller' => 'licencias', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Empresas'), array('controller' => 'empresas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Empresa'), array('controller' => 'empresas', 'action' => 'add')); ?> </li>
	</ul>
</div>
