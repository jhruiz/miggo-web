<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('cuentas/cuentas.js')); ?>
<div class="cuentas form">
<?php echo $this->Form->create('Cuenta', array('class' => 'form-inline')); ?>    
	<fieldset>
            <legend><h2><b><?php echo __('Editar Cuenta'); ?></b></h2></legend>
            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '39', 'id' => 'menuvert'))?>
                <?php echo $this->Form->input('id'); ?>

            <div class="col-md-12" style="margin-bottom: 20px;">

                <div class="form-group col-md-4">
                    <label for="CuentaDescripcion">Nombre Cuenta</label> 
                    <?php echo $this->Form->input('descripcion', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre de la Cuenta')); ?>                   
                </div>
            
                <div class="form-group col-md-4">
                    <label for="CuentaNumerocuenta">Número Cuenta</label> 
                    <?php echo $this->Form->input('numerocuenta', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Número de la Cuenta')); ?>                   
                </div>
            
                <div class="form-group col-md-4">
                    <label for="CuentaSaldo">Saldo</label> 
                        <?php echo $this->Form->input('saldo', array('label' => '', 'type' => 'text', 'class' => 'form-control numericPrice', 'placeholder' => 'Saldo de la Cuenta')); ?>                                       
                </div>
                                      
            </div>
	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>	
		<li><?php echo $this->Html->link(__('Lista Cuentas'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Gastos'), array('controller' => 'gastos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Gasto'), array('controller' => 'gastos', 'action' => 'add')); ?> </li>
	</ul>
</div>
