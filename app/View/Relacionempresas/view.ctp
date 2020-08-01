<?php $this->layout='inicio'; ?>
<div class="relacionempresas view">
<legend><h2><b><?php echo __('Empresa Relacionada'); ?></b></h2></legend>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '37', 'id' => 'menuvert'))?>
<section class="main row">
    <div class="col-md-4"> 
        <?php if($relacionempresa['Relacionempresa']['imagen'] == ""){ ?>
            <?php echo $this->Html->image('png/image-4.png', array('alt' => 'CakePHP', 'width' => '400', 'height' => '500')); ?>  
        <?php }else{?>
            <img src="<?php echo $urlImagen . $relacionempresa['Relacionempresa']['empresa_id'] . "/" . $relacionempresa['Relacionempresa']['imagen'];?>" class="img-responsive img-thumbnail">
        <?php }?>                        
    </div>   
    <div class="col-md-4">    
	<dl>
		<dt class="text-info"><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($relacionempresa['Relacionempresa']['nombre']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Nit'); ?></dt>
		<dd>
			<?php echo h($relacionempresa['Relacionempresa']['nit']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Direccion'); ?></dt>
		<dd>
			<?php echo h($relacionempresa['Relacionempresa']['direccion']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Telefono1'); ?></dt>
		<dd>
			<?php echo h($relacionempresa['Relacionempresa']['telefono1']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($relacionempresa['Relacionempresa']['email']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Representantelegal'); ?></dt>
		<dd>
			<?php echo h($relacionempresa['Relacionempresa']['representantelegal']); ?>
			&nbsp;
		</dd><br>
                <dt class="text-info"><?php echo __('Código'); ?></dt>
		<dd>
			<?php echo h($relacionempresa['Relacionempresa']['codigo']); ?>
			&nbsp;
		</dd><br>                
	</dl>
    </div>
</section>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Empresa Relacionada'), array('action' => 'edit', $relacionempresa['Relacionempresa']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Empresas Relacionadas'), array('action' => 'index')); ?> </li>
	</ul>
</div>
