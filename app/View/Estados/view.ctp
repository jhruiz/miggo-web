<?php $this->layout='inicio'; ?>
<div class="estados view">
<h2><?php echo __('Estado'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($estado['Estado']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripcion'); ?></dt>
		<dd>
			<?php echo h($estado['Estado']['descripcion']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Estado'), array('action' => 'edit', $estado['Estado']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Estado'), array('action' => 'delete', $estado['Estado']['id']), null, __('Are you sure you want to delete # %s?', $estado['Estado']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Estados'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Estado'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tipopagos'), array('controller' => 'tipopagos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tipopago'), array('controller' => 'tipopagos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cargueinventarios'), array('controller' => 'cargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargueinventario'), array('controller' => 'cargueinventarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Depositos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Deposito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Licencias Usuarios'), array('controller' => 'licencias_usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Licencias Usuario'), array('controller' => 'licencias_usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Tipopagos'); ?></h3>
	<?php if (!empty($estado['Tipopago'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Descripcion'); ?></th>
		<th><?php echo __('Contabilizar'); ?></th>
		<th><?php echo __('Estado Id'); ?></th>
		<th><?php echo __('Empresa Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($estado['Tipopago'] as $tipopago): ?>
		<tr>
			<td><?php echo $tipopago['id']; ?></td>
			<td><?php echo $tipopago['descripcion']; ?></td>
			<td><?php echo $tipopago['contabilizar']; ?></td>
			<td><?php echo $tipopago['estado_id']; ?></td>
			<td><?php echo $tipopago['empresa_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'tipopagos', 'action' => 'view', $tipopago['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'tipopagos', 'action' => 'edit', $tipopago['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'tipopagos', 'action' => 'delete', $tipopago['id']), null, __('Are you sure you want to delete # %s?', $tipopago['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Tipopago'), array('controller' => 'tipopagos', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Cargueinventarios'); ?></h3>
	<?php if (!empty($estado['Cargueinventario'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Producto Id'); ?></th>
		<th><?php echo __('Deposito Id'); ?></th>
		<th><?php echo __('Costoproducto'); ?></th>
		<th><?php echo __('Existenciaactual'); ?></th>
		<th><?php echo __('Preciomaximo'); ?></th>
		<th><?php echo __('Preciominimo'); ?></th>
		<th><?php echo __('Precioventa'); ?></th>
		<th><?php echo __('Impuesto Id'); ?></th>
		<th><?php echo __('Usuario Id'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Estado Id'); ?></th>
		<th><?php echo __('Tipopago Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($estado['Cargueinventario'] as $cargueinventario): ?>
		<tr>
			<td><?php echo $cargueinventario['id']; ?></td>
			<td><?php echo $cargueinventario['producto_id']; ?></td>
			<td><?php echo $cargueinventario['deposito_id']; ?></td>
			<td><?php echo $cargueinventario['costoproducto']; ?></td>
			<td><?php echo $cargueinventario['existenciaactual']; ?></td>
			<td><?php echo $cargueinventario['preciomaximo']; ?></td>
			<td><?php echo $cargueinventario['preciominimo']; ?></td>
			<td><?php echo $cargueinventario['precioventa']; ?></td>
			<td><?php echo $cargueinventario['impuesto_id']; ?></td>
			<td><?php echo $cargueinventario['usuario_id']; ?></td>
			<td><?php echo $cargueinventario['created']; ?></td>
			<td><?php echo $cargueinventario['estado_id']; ?></td>
			<td><?php echo $cargueinventario['tipopago_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'cargueinventarios', 'action' => 'view', $cargueinventario['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'cargueinventarios', 'action' => 'edit', $cargueinventario['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'cargueinventarios', 'action' => 'delete', $cargueinventario['id']), null, __('Are you sure you want to delete # %s?', $cargueinventario['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Cargueinventario'), array('controller' => 'cargueinventarios', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Clientes'); ?></h3>
	<?php if (!empty($estado['Cliente'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Nit'); ?></th>
		<th><?php echo __('Nombre'); ?></th>
		<th><?php echo __('Direccion'); ?></th>
		<th><?php echo __('Telefono'); ?></th>
		<th><?php echo __('Ciudade Id'); ?></th>
		<th><?php echo __('Paginaweb'); ?></th>
		<th><?php echo __('Email'); ?></th>
		<th><?php echo __('Celular'); ?></th>
		<th><?php echo __('Diascredito'); ?></th>
		<th><?php echo __('Limitecredito'); ?></th>
		<th><?php echo __('Cumpleanios'); ?></th>
		<th><?php echo __('Observaciones'); ?></th>
		<th><?php echo __('Usuario Id'); ?></th>
		<th><?php echo __('Estado Id'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Empresa Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($estado['Cliente'] as $cliente): ?>
		<tr>
			<td><?php echo $cliente['id']; ?></td>
			<td><?php echo $cliente['nit']; ?></td>
			<td><?php echo $cliente['nombre']; ?></td>
			<td><?php echo $cliente['direccion']; ?></td>
			<td><?php echo $cliente['telefono']; ?></td>
			<td><?php echo $cliente['ciudade_id']; ?></td>
			<td><?php echo $cliente['paginaweb']; ?></td>
			<td><?php echo $cliente['email']; ?></td>
			<td><?php echo $cliente['celular']; ?></td>
			<td><?php echo $cliente['diascredito']; ?></td>
			<td><?php echo $cliente['limitecredito']; ?></td>
			<td><?php echo $cliente['cumpleanios']; ?></td>
			<td><?php echo $cliente['observaciones']; ?></td>
			<td><?php echo $cliente['usuario_id']; ?></td>
			<td><?php echo $cliente['estado_id']; ?></td>
			<td><?php echo $cliente['created']; ?></td>
			<td><?php echo $cliente['empresa_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'clientes', 'action' => 'view', $cliente['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'clientes', 'action' => 'edit', $cliente['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'clientes', 'action' => 'delete', $cliente['id']), null, __('Are you sure you want to delete # %s?', $cliente['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Depositos'); ?></h3>
	<?php if (!empty($estado['Deposito'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Descripcion'); ?></th>
		<th><?php echo __('Empresa Id'); ?></th>
		<th><?php echo __('Ciudade Id'); ?></th>
		<th><?php echo __('Estado Id'); ?></th>
		<th><?php echo __('Telefono'); ?></th>
		<th><?php echo __('Direccion'); ?></th>
		<th><?php echo __('Usuario Id'); ?></th>
		<th><?php echo __('Nombredocumentoventa'); ?></th>
		<th><?php echo __('Resolucionfacturacion'); ?></th>
		<th><?php echo __('Tipodeposito Id'); ?></th>
		<th><?php echo __('Fecharesolucion'); ?></th>
		<th><?php echo __('Prefijo'); ?></th>
		<th><?php echo __('Regimene Id'); ?></th>
		<th><?php echo __('Nota'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($estado['Deposito'] as $deposito): ?>
		<tr>
			<td><?php echo $deposito['id']; ?></td>
			<td><?php echo $deposito['descripcion']; ?></td>
			<td><?php echo $deposito['empresa_id']; ?></td>
			<td><?php echo $deposito['ciudade_id']; ?></td>
			<td><?php echo $deposito['estado_id']; ?></td>
			<td><?php echo $deposito['telefono']; ?></td>
			<td><?php echo $deposito['direccion']; ?></td>
			<td><?php echo $deposito['usuario_id']; ?></td>
			<td><?php echo $deposito['nombredocumentoventa']; ?></td>
			<td><?php echo $deposito['resolucionfacturacion']; ?></td>
			<td><?php echo $deposito['tipodeposito_id']; ?></td>
			<td><?php echo $deposito['fecharesolucion']; ?></td>
			<td><?php echo $deposito['prefijo']; ?></td>
			<td><?php echo $deposito['regimene_id']; ?></td>
			<td><?php echo $deposito['nota']; ?></td>
			<td><?php echo $deposito['created']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'depositos', 'action' => 'view', $deposito['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'depositos', 'action' => 'edit', $deposito['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'depositos', 'action' => 'delete', $deposito['id']), null, __('Are you sure you want to delete # %s?', $deposito['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Deposito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Licencias Usuarios'); ?></h3>
	<?php if (!empty($estado['LicenciasUsuario'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Fechainicio'); ?></th>
		<th><?php echo __('Fechafin'); ?></th>
		<th><?php echo __('Licencia Id'); ?></th>
		<th><?php echo __('Usuario Id'); ?></th>
		<th><?php echo __('Estado Id'); ?></th>
		<th><?php echo __('Codigo'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($estado['LicenciasUsuario'] as $licenciasUsuario): ?>
		<tr>
			<td><?php echo $licenciasUsuario['id']; ?></td>
			<td><?php echo $licenciasUsuario['fechainicio']; ?></td>
			<td><?php echo $licenciasUsuario['fechafin']; ?></td>
			<td><?php echo $licenciasUsuario['licencia_id']; ?></td>
			<td><?php echo $licenciasUsuario['usuario_id']; ?></td>
			<td><?php echo $licenciasUsuario['estado_id']; ?></td>
			<td><?php echo $licenciasUsuario['codigo']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'licencias_usuarios', 'action' => 'view', $licenciasUsuario['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'licencias_usuarios', 'action' => 'edit', $licenciasUsuario['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'licencias_usuarios', 'action' => 'delete', $licenciasUsuario['id']), null, __('Are you sure you want to delete # %s?', $licenciasUsuario['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Licencias Usuario'), array('controller' => 'licencias_usuarios', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Usuarios'); ?></h3>
	<?php if (!empty($estado['Usuario'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Nombre'); ?></th>
		<th><?php echo __('Identificacion'); ?></th>
		<th><?php echo __('Username'); ?></th>
		<th><?php echo __('Imagen'); ?></th>
		<th><?php echo __('Perfile Id'); ?></th>
		<th><?php echo __('Password'); ?></th>
		<th><?php echo __('Estado Id'); ?></th>
		<th><?php echo __('Estadologin'); ?></th>
		<th><?php echo __('Empresa Id'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($estado['Usuario'] as $usuario): ?>
		<tr>
			<td><?php echo $usuario['id']; ?></td>
			<td><?php echo $usuario['nombre']; ?></td>
			<td><?php echo $usuario['identificacion']; ?></td>
			<td><?php echo $usuario['username']; ?></td>
			<td><?php echo $usuario['imagen']; ?></td>
			<td><?php echo $usuario['perfile_id']; ?></td>
			<td><?php echo $usuario['password']; ?></td>
			<td><?php echo $usuario['estado_id']; ?></td>
			<td><?php echo $usuario['estadologin']; ?></td>
			<td><?php echo $usuario['empresa_id']; ?></td>
			<td><?php echo $usuario['created']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'usuarios', 'action' => 'view', $usuario['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'usuarios', 'action' => 'edit', $usuario['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'usuarios', 'action' => 'delete', $usuario['id']), null, __('Are you sure you want to delete # %s?', $usuario['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
