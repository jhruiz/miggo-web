<?php $this->layout='inicio'; ?>
<div class="empresas index">
    
            <?php echo $this->Form->create('Empresas',array('action'=>'search','method'=>'post'));?>
            <legend><h2><b><?php echo __('Buscar Empresas'); ?></b></h2></legend>   
            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '41', 'id' => 'menuvert'))?>   
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group ">  
                        <label>Nombre</label><br>                          
                        <input name="nombre" id="nombre" class="form-control" placeholder="Nombre de la Empresa" type="text">
                    </div>             
                </div>       

                
                <div class="col-md-3">
                    <div class="form-group ">  
                        <label>NIT</label><br>                          
                        <input name="nit" id="nit" class="form-control" placeholder="NIT de la Empresa" type="text">
                    </div>             
                </div>
                
                <div class="col-md-6">
                    &nbsp;
                </div>               
        </div>        
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
            
	<legend><h2><b><?php echo __('Empresas'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">
                <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                    <tr>
                                    <th><?php echo $this->Paginator->sort('nombre'); ?></th>
                                    <th><?php echo $this->Paginator->sort('nit'); ?></th>
                                    <th><?php echo $this->Paginator->sort('direccion', 'Dirección'); ?></th>
                                    <th><?php echo $this->Paginator->sort('telefono1', 'Teléfono 1'); ?></th>
                                    <th><?php echo $this->Paginator->sort('telefono2', 'Teléfono 2'); ?></th>
                                    <th><?php echo $this->Paginator->sort('email'); ?></th>
                                    <th><?php echo $this->Paginator->sort('representantelegal', 'Representante Legal'); ?></th>
                                    <th><?php echo $this->Paginator->sort('ciudade_id', 'Ciudad'); ?></th>
                                    <th class="actions"><?php echo __('Acciones'); ?></th>
                    </tr>
                    <?php foreach ($empresas as $empresa): ?>
                    <tr>
                            <td><?php echo h($empresa['Empresa']['nombre']); ?>&nbsp;</td>
                            <td><?php echo h($empresa['Empresa']['nit']); ?>&nbsp;</td>
                            <td><?php echo h($empresa['Empresa']['direccion']); ?>&nbsp;</td>
                            <td><?php echo h($empresa['Empresa']['telefono1']); ?>&nbsp;</td>
                            <td><?php echo h($empresa['Empresa']['telefono2']); ?>&nbsp;</td>
                            <td><?php echo h($empresa['Empresa']['email']); ?>&nbsp;</td>
                            <td><?php echo h($empresa['Empresa']['representantelegal']); ?>&nbsp;</td>
                            <td>
                                    <?php echo $this->Html->link($empresa['Ciudade']['descripcion'], array('controller' => 'ciudades', 'action' => 'view', $empresa['Empresa']['id'])); ?>
                            </td>
                            <td class="actions">
                                <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Empresa', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $empresa['Empresa']['id']))); ?>
                                <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Empresa', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $empresa['Empresa']['id']))); ?>                   
                                <?php
                                echo $this->Form->postLink(                        
                                  $this->Html->image('png/list-2.png', array('title' => 'Eliminar Empresa', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                                  array('action' => 'delete', $empresa['Empresa']['id']), //url
                                  array('escape' => false), //el escape
                                  __('Está seguro que desea eliminar la empresa %s?', $empresa['Empresa']['nombre']) //la confirmacion
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
	?>	
        </p>
	<div class="pagin">
	<?php echo $this->Paginator->prev('< ' . __('Anterior '), array(), null, array('class' => 'prev disabled')); ?>
	<?php echo $this->Paginator->numbers(array('separator' => ' || ')); ?>
	<?php echo $this->Paginator->next(__(' Siguiente') . ' >', array(), null, array('class' => 'next disabled')); ?>
	</div>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Nueva Empresa'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Impuestos'), array('controller' => 'impuestos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Impuesto'), array('controller' => 'impuestos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Tipos de Pago'), array('controller' => 'tipopagos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Tipo de Pago'), array('controller' => 'tipopagos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Depósitos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Depósito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Categorias'), array('controller' => 'categorias', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Categoria'), array('controller' => 'categorias', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Tipo Depósitos'), array('controller' => 'tipodepositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Tipo Depósito'), array('controller' => 'tipodepositos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Perfiles'), array('controller' => 'perfiles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Perfile'), array('controller' => 'perfiles', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
