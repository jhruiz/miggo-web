<?php echo ($this->Html->script('bandeja/gestionBandejas'));  ?>
<?php $this->layout='inicio'; ?>
<div class="depositos form">
<div class="container-fluid">
<?php echo $this->Form->create('Deposito', array('class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Editar Depósito'); ?></b></h2></legend>
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '15', 'id' => 'menuvert'))?>
                <?php echo $this->Form->input('id');?>
                <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId)); ?>
                
                <div class="row">
                    <div class="form-group">
                        <label for="DepositoDescripcion">Nombre</label>
                        <?php echo $this->Form->input('descripcion', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Nombre del Depósito')); ?>
                    </div>                
                </div><br>                
                
                <div class="row"> 
                    <div class="form-group">
                        <label for="DepositoCiudadeId">Ciudad</label>
                        <?php echo $this->Form->input('ciudade_id', array('class' => 'form-control', 'label' => '')); ?>
                    </div>      
                </div><br>      
                
                <div class="row"> 
                    <div class="form-group"> 
                        <label for="DepositoEstadoId">Estado</label>
                        <?php echo $this->Form->input('estado_id', array('class' => 'form-control', 'label' => '')); ?>
                    </div>  
                </div><br>  
                
                <div class="row">
                    <div class="form-group">
                        <label for="DepositoTelefono">Teléfono</label>
                        <?php echo $this->Form->input('telefono', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Teléfono del Depósito')); ?>
                    </div>                 
                </div><br>                 
                
                <div class="row">
                    <div class="form-group">
                        <label for="DepositoDireccion">Dirección</label>
                        <?php echo $this->Form->input('direccion', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Dirección del Depósito')); ?>
                    </div>  
                </div><br>  

                <div class="row"> 
                    <div class="form-group">
                        <label for="DepositoUsuarioId">Encargado</label>
                        <?php echo $this->Form->input('usuario_id', array('label' => '', 'class' => 'form-control')); ?>
                    </div>    
                </div><br>    
                
                <div class="row">
                    <div class="form-group">
                        <label for="DepositoNombredocumentoventa">Dcto. Venta</label>
                        <?php echo $this->Form->input('nombredocumentoventa', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre Documento')); ?>
                    </div>     
                </div><br>     
                
                <div class="row">
                    <div class="form-group">
                        <label for="DepositoResolucionfacturacion">Resolución Fact.</label>
                        <?php echo $this->Form->input('resolucionfacturacion', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Resolución Factura')); ?>
                    </div> 
                </div><br> 
                
                <div class="row"> 
                    <div class="form-group"> 
                        <label for="DepositoTipodepositoId">Tipo Depósito</label>
                        <?php echo $this->Form->input('tipodeposito_id', array('label' => '', 'class' => 'form-control')); ?>
                    </div>  
                </div><br>  
                
                <div class="row">     
                    <div class="form-group">     
                        <label for="cumpleanios">Fecha de Resolución</label><br>
                        <input name="data[Deposito][fecharesolucion]" class="date form-control" placeholder="Fecha de Resolución" type="text" id="cumpleanios">
                    </div>       
                </div><br>       
                
                <div class="row">
                    <div class="form-group">
                        <label for="DepositoResolucioninicia">Inicio Resolución</label>
                        <?php echo $this->Form->input('resolucioninicia', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Número Inicial de Resolución')); ?>
                    </div>
                </div><br>
                
                <div class="row">
                    <div class="form-group">
                        <label for="DepositoResolucionfin">Fin Resolución</label>
                        <?php echo $this->Form->input('resolucionfin', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Número Final de Resolución')); ?>
                    </div>                 
                </div><br>                 

                <div class="row"> 
                    <div class="form-group">
                        <label for="DepositoPrefijo">Prefijo</label>
                        <?php echo $this->Form->input('prefijo', array('class' => 'form-control', 'label' => '', 'placeholder' => 'Prefijo del Depósito')); ?>
                    </div>      
                </div><br>      
                
                <div class="row"> 
                    <div class="form-group">
                        <label for="DepositoRegimeneId">Régimen</label>
                        <?php echo $this->Form->input('regimene_id', array('label' => '', 'class' => 'form-control')); ?>
                    </div>     
                </div><br>   

                <div class="row">
                    <div class="form-group"> 
                        <label>Ver en estadísticas</label>
                        <?php echo $this->Form->input('estadisticas', array('label' => '', 'type' => 'checkbox', 'class' => 'form-control')); ?>
                    </div>
                </div>                  
                
                <div class="row">                     
                    <div class="form-group">
                        <label for="DepositoNota">Nota</label>
                        <?php echo $this->Form->input('nota', array('class' => 'form-control', 'label' => '', 'placeholder' => 'Agregar nota para el depósito')); ?>
                    </div> 
                </div><br><br> 
	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Depositós'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Tipo Depósitos'), array('controller' => 'tipodepositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Tipo Depósito'), array('controller' => 'tipodepositos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Cargue Inventarios'), array('controller' => 'cargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cargue Inventario'), array('controller' => 'cargueinventarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Descargue Inventarios'), array('controller' => 'descargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Descargue Inventario'), array('controller' => 'descargueinventarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
	</ul>
</div>
