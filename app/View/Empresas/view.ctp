<?php $this->layout = 'inicio';?>
<div class="empresas view">
<legend><h2><b><?php echo __('Empresa'); ?></b></h2></legend>
<!--Enlaces de acción -->
<div class="actions text-left">
    <button type="button" class="btn btn-success">
    	<?php echo $this->Html->link(__('Editar Empresa'), array('action' => 'edit', $empresa['Empresa']['id']), ["style" => "color:white;"]); ?>
    </button>
</div>

<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '41', 'id' => 'menuvert')) ?>
<section class="main row">
    <div class="col-md-4">
        <?php if ($empresa['Empresa']['imagen'] == "") {?>
            <?php echo $this->Html->image('png/image-4.png', array('alt' => 'CakePHP', 'width' => '400', 'height' => '500')); ?>
        <?php } else {?>
            <img src="<?php echo $urlImagen . $empresa['Empresa']['id'] . "/" . $empresa['Empresa']['imagen']; ?>" class="img-responsive img-thumbnail">
        <?php }?>
    </div>
    <div class="col-md-4">
	<dl>
		<dt class="text-info"><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($empresa['Empresa']['nombre']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Nit'); ?></dt>
		<dd>
			<?php echo h($empresa['Empresa']['nit']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Direccion'); ?></dt>
		<dd>
			<?php echo h($empresa['Empresa']['direccion']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Telefono1'); ?></dt>
		<dd>
			<?php echo h($empresa['Empresa']['telefono1']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Telefono2'); ?></dt>
		<dd>
			<?php echo h($empresa['Empresa']['telefono2']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($empresa['Empresa']['email']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Representantelegal'); ?></dt>
		<dd>
			<?php echo h($empresa['Empresa']['representantelegal']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Ciudade'); ?></dt>
		<dd>
			<?php echo $this->Html->link($empresa['Ciudade']['descripcion'], array('controller' => 'ciudades', 'action' => 'view', $empresa['Ciudade']['id'])); ?>
			&nbsp;
		</dd><br>
	</dl>
    </div>
</section>
</div>