<?php $this->layout='inicio'; ?>
<div class="proveedores index">
    
            <?php echo $this->Form->create('Proveedores',array('action'=>'search','method'=>'post', 'class' => 'form-inline'));?>
            <legend><h2><b><?php echo __('Buscar Proveedores'); ?></b></h2></legend> 
            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '27', 'id' => 'menuvert'))?>     
            
            
            <div class="form-group">
                <label for="nombre">Nombre</label><br>
                <input name="nombre" id="nombre" class="form-control" placeholder="Nombre Proveedor" type="text">
            </div>

            <div class="form-group">
                <label for="nit">NIT</label><br>
                <input name="nit" id="nit" class="form-control" placeholder="NIT Proveedor" type="text">
            </div>

            <div class="form-group">
                <label for="ProveedoresCiudad">Ciudad</label>
                <?php echo $this->Form->input('ciudad', array('label' => '', 'name' => 'ciudad', 'empty' => 'Seleccione una', 'type' => 'select', 'options' => $ciudades, 'class' => 'form-control'));?>
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
        
	<legend><h2><b><?php echo __('Proveedores'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">
                <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                    <tr>
                                    <th><?php echo $this->Paginator->sort('nit'); ?></th>
                                    <th><?php echo $this->Paginator->sort('nombre'); ?></th>
                                    <th><?php echo h('Codigo'); ?></th>
                                    <th><?php echo $this->Paginator->sort('direccion'); ?></th>
                                    <th><?php echo $this->Paginator->sort('telefono'); ?></th>
                                    <th><?php echo $this->Paginator->sort('ciudade_id'); ?></th>
                                    <th><?php echo $this->Paginator->sort('celular'); ?></th>
                                    <th><?php echo $this->Paginator->sort('estado_id'); ?></th>
                                    <th class="actions"><?php echo __('Acciones'); ?></th>
                    </tr>
                    <?php foreach ($proveedores as $proveedore): ?>
                    <tr>
                            <td><?php echo h($proveedore['Proveedore']['nit']); ?>&nbsp;</td>
                            <td><?php echo h($proveedore['Proveedore']['nombre']); ?>&nbsp;</td>
                            <td><?php echo h($proveedore['Proveedore']['empresa_id'] . $proveedore['Proveedore']['usuario_id'] . '-' . $proveedore['Proveedore']['id']); ?>&nbsp;</td>
                            <td><?php echo h($proveedore['Proveedore']['direccion']); ?>&nbsp;</td>
                            <td><?php echo h($proveedore['Proveedore']['telefono']); ?>&nbsp;</td>
                            <td>
                                    <?php echo $this->Html->link($proveedore['Ciudade']['descripcion'], array('controller' => 'ciudades', 'action' => 'view', $proveedore['Ciudade']['id'])); ?>
                            </td>
                            <td><?php echo h($proveedore['Proveedore']['celular']); ?>&nbsp;</td>
                            <td>
                                    <?php echo $this->Html->link($proveedore['Estado']['descripcion'], array('controller' => 'estados', 'action' => 'view', $proveedore['Estado']['id'])); ?>
                            </td>                            
                            <td class="actions">
                                <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Proveedor', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $proveedore['Proveedore']['id']))); ?>
                                <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Proveedor', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $proveedore['Proveedore']['id']))); ?>                   
                                <?php
                                echo $this->Form->postLink(                        
                                  $this->Html->image('png/list-2.png', array('title' => 'Eliminar Proveedor', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                                  array('action' => 'delete', $proveedore['Proveedore']['id']), //url
                                  array('escape' => false), //el escape
                                  __('Está seguro que desea eliminar el proveedor %s?', $proveedore['Proveedore']['nombre']) //la confirmacion
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
	<?php echo $this->Paginator->prev('< ' . __('Anterior '), array(), null, array('class' => 'prev disabled')); ?>
	<?php echo $this->Paginator->numbers(array('separator' => ' || ')); ?>
	<?php echo $this->Paginator->next(__(' Siguiente') . ' >', array(), null, array('class' => 'next disabled')); ?>
	</div>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Nuevo Proveedor'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Ciudades'), array('controller' => 'ciudades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Ciudad'), array('controller' => 'ciudades', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
