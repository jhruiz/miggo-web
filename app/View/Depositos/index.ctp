<?php $this->layout='inicio'; ?>
<div class="depositos index">
    
            <?php echo $this->Form->create('Depositos',array('action'=>'search','method'=>'post', 'class' => 'form-inline'));?>
            <legend><h2><b><?php echo __('Buscar Depósitos'); ?></b></h2></legend>   
            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '15', 'id' => 'menuvert'))?>   
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group ">  
                        <label>Nombre</label><br>                          
                        <input name="nombre" id="nombre" class="form-control" placeholder="Nombre del Depósito" type="text">
                    </div>             
                </div>       

                
                <div class="col-md-3">
                    <div class="form-group ">  
                        <label>Ciudad</label><br>                          
                        <?php echo $this->Form->input('ciudad', array('label' => '', 'name' => 'ciudad', 'empty' => 'Seleccione una', 'type' => 'select', 'options' => $ciudades, 'class' => 'form-control'));?>
                    </div>             
                </div>
                
                <div class="col-md-3">
                    <div class="form-group ">  
                        <label>Encargado</label><br>                          
                        <?php echo $this->Form->input('encargado', array('label' => '', 'name' => 'encargado', 'empty' => 'Seleccione uno', 'type' => 'select', 'options' => $usuarios, 'class' => 'form-control'));?>
                    </div>             
                </div>

                <div class="col-md-3">
                    &nbsp;            
                </div>                             
            </div><br><br>        
        <div class="row">
            <div class="col-md-3">
                <div class="form-group ">  
                <?php echo $this->Form->submit('Buscar',array('class'=>'btn btn-primary'));?>                
                </div>             
            </div>
            <div class="col-md-9">
                &nbsp;
            </div>
        </div>            

        </form><br><br>              
            
	<legend><h2><b><?php echo __('Depósitos'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">         
	<table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
	<tr>
			<th><?php echo $this->Paginator->sort('descripcion', 'Nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('ciudade_id', 'Ciudad'); ?></th>
			<th><?php echo $this->Paginator->sort('telefono', 'Teléfono'); ?></th>
			<th><?php echo $this->Paginator->sort('direccion', 'Dirección'); ?></th>
			<th><?php echo $this->Paginator->sort('usuario_id', 'Encargado'); ?></th>
			<th><?php echo ('Código'); ?></th>
			<th class="actions"><?php echo __('Acciones'); ?></th>
	</tr>
	<?php foreach ($depositos as $deposito): ?>
	<tr>
		<td><?php echo h($deposito['Deposito']['descripcion']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($deposito['Ciudade']['descripcion'], array('controller' => 'ciudades', 'action' => 'view', $deposito['Ciudade']['id'])); ?>
		</td>
		<td><?php echo h($deposito['Deposito']['telefono']); ?>&nbsp;</td>
		<td><?php echo h($deposito['Deposito']['direccion']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($deposito['Usuario']['nombre'], array('controller' => 'usuarios', 'action' => 'view', $deposito['Usuario']['id'])); ?>
		</td>
		<td>
		    <?php echo h($deposito['Deposito']['empresa_id'] . $deposito['Deposito']['ciudade_id'] . '-' . $deposito['Deposito']['id'])?>
		</td>
		<td class="actions">                    
                    <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Depósito', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $deposito['Deposito']['id']))); ?>
                    <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Depósito', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $deposito['Deposito']['id']))); ?>                   
                    <?php
                    echo $this->Form->postLink(                        
                      $this->Html->image('png/list-2.png', array('title' => 'Eliminar Depósito', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                      array('action' => 'delete', $deposito['Deposito']['id']), //url
                      array('escape' => false), //el escape
                      __('Está seguro que desea eliminar el depósito %s?', $deposito['Deposito']['descripcion']) //la confirmacion
                    ); 
                    ?>                          
		</td>
	</tr>
<?php endforeach; ?>
	</table>
            </div>
        </div>
        <p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Página {:page} de {:pages}, mostrando {:current} registro de {:count} en total, iniciando en registro {:start}, finalizando en {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('Anterior '), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ' || '));
		echo $this->Paginator->next(__(' Siguiente') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Nuevo Depósito'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Tipos Depósitos'), array('controller' => 'tipodepositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Tipo Depósito'), array('controller' => 'tipodepositos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Régimen'), array('controller' => 'regimenes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Régimen'), array('controller' => 'regimenes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Cargue Inventarios'), array('controller' => 'cargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cargue Inventario'), array('controller' => 'cargueinventarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Descargue Inventarios'), array('controller' => 'descargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Descargue Inventario'), array('controller' => 'descargueinventarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
	</ul>
</div>
