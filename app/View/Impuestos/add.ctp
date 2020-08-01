<?php $this->layout='inicio'; ?>
<div class="impuestos form">
<?php echo $this->Form->create('Impuesto', array('class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Agregar Impuesto'); ?></b></h2></legend>
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '20', 'id' => 'menuvert'))?>
                                
                <div>                                    
                    <div class="form-group">
                        <label for="ImpuestoDescripcion">Nombre</label>
                        <?php echo $this->Form->input('descripcion', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Nombre del Impuesto')); ?>
                    </div>

                    <div class="form-group">
                        <label for="ImpuestoValor">Valor</label>
                        <?php echo $this->Form->input('valor', array('label' => '', 'class' => 'form-control', 'placeholder' => '% Valor')); ?>
                    </div>                                                                         
                </div><br>
                
                    <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId)); ?>
	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>

		<li><?php echo $this->Html->link(__('Lista Impuestos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Cargue Inventarios'), array('controller' => 'cargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cargue Inventario'), array('controller' => 'cargueinventarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
