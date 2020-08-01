<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('gastos/gastos')); ?>
<?php echo ($this->Html->script('bandeja/gestionBandejas'));  ?>
<div class="gastos form">
<?php echo $this->Form->create('Gasto', array('class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Agregar Gasto'); ?></b></h2></legend>
                <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '40', 'id' => 'menuvert'))?>
                
                <div class="col-md-12" style="margin-bottom: 20px;">
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="GastoUsuarioId">Usuario</label>
                            <?php echo $this->Form->input('usuario_id', array('class' => 'form-control', 'label' => '', 'empty' => 'Seleccione Uno')); ?>
                        </div>                        
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="empresa">Empresa</label><br>
                            <select class="form-control" name="data[Gasto][empresagasto]" id="empresa">
                                <?php foreach ($arrEmpresa as $em){?>
                                <option value="<?php echo ($em['id'] . "_" . $em['tipo'])?>"><?php echo ($em['nombre'])?></option>
                                <?php } ?>
                            </select>                    
                        </div>                        
                    </div>
                    
                </div>
                
                <div class="col-md-12" style="margin-bottom: 20px;">
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fechagasto">Fecha Gasto</label><br>
                            <input name="data[Gasto][fechagasto]" class="date form-control" placeholder="Fecha del Gasto" type="text" id="fechagasto" autocomplete="off">
                        </div>                         
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="GastoItemsgastoId">Items</label>
                            <?php echo $this->Form->input('itemsgasto_id', array('label' => '', 'empty' => 'Seleccione uno', 'type' => 'select', 'options' => $itemsGasto, 'class' => 'form-control'));?>
                        </div>                         
                    </div>
                    
                </div>
                
                <div class="col-md-12" style="margin-bottom: 20px;">
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="GastoValor">Valor</label><br>
                            <?php echo $this->Form->input('valor', array('label' => '', 'type' => 'text', 'class' => 'form-control numberPrice', 'placeholder' => 'Monto del gasto', 'onblur' => 'restaurarCuenta();', 'autocomplete' => 'off')); ?>
                        </div>                          
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="GastoCuentaId">Cuenta Origen</label><br>
                            <?php echo $this->Form->input('cuenta_id', array('label' => '','class' => 'form-control', 'empty' => 'Seleccione Una', 'onchange' => 'validarEstadoCuenta();')); ?>
                        </div>                          
                    </div>
                    
                </div>
                
                <div class="col-md-12" style="margin-bottom: 20px;">
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo $this->Form->input('traslado', array('class' => 'form-control', 'type' => 'checkbox')); ?>
                        </div>                                                 
                    </div>
                    
                    <div class="col-md-6 cuentadestino">
                        <div class="form-group">
                            <label for="GastoCuentadestino">Cuenta Destino</label><br>
                            <?php echo $this->Form->input('cuentadestino', array('label' => '','class' => 'form-control', 'type' => 'select')); ?>
                        </div>                         
                    </div>
                    
                </div>
                
                <div class="col-md-12" style="margin-bottom: 20px;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="GastoDescripcion">Descripción</label><br>
                            <?php echo $this->Form->input('descripcion', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Descripción del gasto')); ?>
                        </div>                        
                    </div>
                </div>
                               
                
                <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId)); ?>
	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?> 
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Gastos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
