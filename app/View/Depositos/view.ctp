<?php $this->layout='inicio'; ?>
<div class="depositos view">
<legend><h2><b><?php echo __('Depósito'); ?></b></h2></legend>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '15', 'id' => 'menuvert'))?>
	<dl>
<section class="main row">
    <div class="col-md-4">
        <dt class="text-info"><?php echo __('Nombre'); ?></dt>
        <dd>
                <?php echo h($deposito['Deposito']['descripcion']); ?>
                &nbsp;
        </dd><br>
        <dt class="text-info"><?php echo __('Teléfono'); ?></dt>
        <dd>
                <?php echo h($deposito['Deposito']['telefono']); ?>
                &nbsp;
        </dd><br>   
        <dt class="text-info"><?php echo __('Nombre Documento de Venta'); ?></dt>
        <dd>
                <?php echo h($deposito['Deposito']['nombredocumentoventa']); ?>
                &nbsp;
        </dd><br>  
        <dt class="text-info"><?php echo __('Fecha Resolución'); ?></dt>
        <dd>
                <?php echo h($deposito['Deposito']['fecharesolucion']); ?>
                &nbsp;
        </dd><br>    
        <dt class="text-info"><?php echo __('Nota'); ?></dt>
        <dd>
                <?php echo h($deposito['Deposito']['nota']); ?>
                &nbsp;
        </dd><br>          
    </div>
    
    <div class="col-md-4">
        <dt class="text-info"><?php echo __('Ciudad'); ?></dt>
        <dd>
                <?php echo $this->Html->link($deposito['Ciudade']['descripcion'], array('controller' => 'ciudades', 'action' => 'view', $deposito['Ciudade']['id'])); ?>
                &nbsp;
        </dd><br>  
        <dt class="text-info"><?php echo __('Dirección'); ?></dt>
        <dd>
                <?php echo h($deposito['Deposito']['direccion']); ?>
                &nbsp;
        </dd><br>  
        <dt class="text-info"><?php echo __('Resolución Facturación'); ?></dt>
        <dd>
                <?php echo h($deposito['Deposito']['resolucionfacturacion']); ?>
                &nbsp;
        </dd><br>     
        <dt class="text-info"><?php echo __('Prefijo'); ?></dt>
        <dd>
                <?php echo h($deposito['Deposito']['prefijo']); ?>
                &nbsp;
        </dd><br> 
        <dt class="text-info"><?php echo __('# Inicio Resolución'); ?></dt>
        <dd>
                <?php echo h($deposito['Deposito']['resolucioninicia']); ?>
                &nbsp;
        </dd><br>          
      
    </div>
    
    <div class="col-md-4">
        <dt class="text-info"><?php echo __('Estado'); ?></dt>
        <dd>
                <?php echo $this->Html->link($deposito['Estado']['descripcion'], array('controller' => 'estados', 'action' => 'view', $deposito['Estado']['id'])); ?>
                &nbsp;
        </dd><br> 
        <dt class="text-info"><?php echo __('Usuario Encargado'); ?></dt>
        <dd>
                <?php echo $this->Html->link($deposito['Usuario']['nombre'], array('controller' => 'usuarios', 'action' => 'view', $deposito['Usuario']['id'])); ?>
                &nbsp;
        </dd><br>  
        <dt class="text-info"><?php echo __('Tipo Depósito'); ?></dt>
        <dd>
                <?php echo $this->Html->link($deposito['Tipodeposito']['descripcion'], array('controller' => 'tipodepositos', 'action' => 'view', $deposito['Tipodeposito']['id'])); ?>
                &nbsp;
        </dd><br>    
        <dt class="text-info"><?php echo __('Régimen'); ?></dt>
        <dd>
                <?php echo $this->Html->link($deposito['Regimene']['descripcion'], array('controller' => 'regimenes', 'action' => 'view', $deposito['Regimene']['id'])); ?>
                &nbsp;
        </dd><br>        
        <dt class="text-info"><?php echo __('# Fin Resolución'); ?></dt>
        <dd>
                <?php echo h($deposito['Deposito']['resolucionfin']); ?>
                &nbsp;
        </dd><br>         
    </div>    
</section>

	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Depósito'), array('action' => 'edit', $deposito['Deposito']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Depósitos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Depósito'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Tipo Depósitos'), array('controller' => 'tipodepositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Tipo depósito'), array('controller' => 'tipodepositos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Cargue Inventarios'), array('controller' => 'cargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cargue Inventario'), array('controller' => 'cargueinventarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Descargue Inventarios'), array('controller' => 'descargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Descargue Inventario'), array('controller' => 'descargueinventarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
	</ul>
</div>