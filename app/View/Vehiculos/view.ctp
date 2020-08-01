<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('vehiculos/view_vehiculo.js')); ?>
<div>
<legend><h2><b><?php echo __('Vehículo'); ?></b></h2></legend>

<div class="col-md-3"></div>
	<div class="qrcode col-md-3" id="qr"> </div>
		<div class="col-md-3">
			<dl>
				<input name="placacodificado" id="placacodificado"  type="hidden" value="<?php echo h($vehiculo['Vehiculo']['placa']);  ?>">

				<dt class="text-info"><?php echo __('Tipo vehículo'); ?></dt>
				<dd>
					<?php echo h($arrTipV[$vehiculo['Vehiculo']['tipovehiculo_id']]); ?>
					&nbsp;
				</dd><br>
						
				<dt class="text-info"><?php echo __('Placa');?></dt>
				<dd>
					<?php echo h($vehiculo['Vehiculo']['placa']); ?>
					&nbsp;
				</dd><br>
						
				<dt class="text-info"><?php echo __('Marca'); ?></dt>
				<dd>
					<?php echo h($arrMarcas[$vehiculo['Vehiculo']['marcavehiculo_id']]); ?>
					&nbsp;
				</dd><br>
						
				<dt class="text-info"><?php echo __('Linea'); ?></dt>
				<dd>
					<?php echo h($vehiculo['Vehiculo']['linea']); ?>
					&nbsp;
				</dd><br>
						
				<dt class="text-info"><?php echo __('Cilindraje'); ?></dt>
				<dd>
					<?php echo h($vehiculo['Vehiculo']['cilindraje']); ?>
					&nbsp;
				</dd><br>
						
				<dt class="text-info"><?php echo __('Modelo'); ?></dt>
				<dd>
					<?php echo h($vehiculo['Vehiculo']['modelo']); ?>
					&nbsp;
				</dd><br>
						
				<dt class="text-info"><?php echo __('Color'); ?></dt>
				<dd>
					<?php echo h($vehiculo['Vehiculo']['color']); ?>
					&nbsp;
				</dd><br>
						
				<dt class="text-info"><?php echo __('Num. Motor'); ?></dt>
				<dd>
					<?php echo h($vehiculo['Vehiculo']['num_motor']); ?>
					&nbsp;
				</dd><br>
						
				<dt class="text-info"><?php echo __('Num. Chasis'); ?></dt>
				<dd>
					<?php echo h($vehiculo['Vehiculo']['num_chasis']); ?>
					&nbsp;
				</dd><br>
				<dt class="text-info"><?php echo __('Fecha Vence Soat'); ?></dt>
				<dd>
					<?php echo h($vehiculo['Vehiculo']['soat']); ?>
					&nbsp;
				</dd><br>
				<dt class="text-info"><?php echo __('Fecha Vence Tecnomecánica'); ?></dt>
				<dd>
					<?php echo h($vehiculo['Vehiculo']['tecno']); ?>
					&nbsp;
				</dd><br>
			</dl>	
		</div>
	<div class="col-md-3"></div>
</div>

<div class="container">
	<button type="button" class="btn btn-primary" id="print_qr">Imprimir QR</button>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Vehículo'), array('action' => 'edit', $vehiculo['Vehiculo']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Vehículos'), array('action' => 'index')); ?> </li>
	</ul>
</div>
