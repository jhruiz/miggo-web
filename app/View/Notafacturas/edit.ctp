<?php $this->layout='inicio'; ?>
<div class="notafacturas form">
<?php echo $this->Form->create('Notafactura'); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Editar Nota Factura'); ?></b></h2></legend>
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '36', 'id' => 'menuvert'))?>
            <?php
                    echo $this->Form->input('id');
            ?>
            <div class="form-group">
                <?php echo $this->Form->input('descripcion', array('label' => 'Nota Factura', 'class' => 'form-control', 'placeholder' => 'Nota de Factura')); ?>
            </div>                
	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
            <li><?php echo $this->Html->link(__('Lista Nota de Facturas'), array('action' => 'index')); ?></li>

	</ul>
</div>
