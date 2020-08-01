<?php $this->layout='inicio'; ?>
<div class="cloudmenusPerfiles index">
    
            <?php echo $this->Form->create('CloudmenusPerfiles',array('action'=>'search','method'=>'post'));?>
            <legend><h2><b><?php echo __('Buscar Productos'); ?></b></h2></legend>      
            <div class="row">                
                <div class="col-md-3">
                    <div class="form-group ">  
                        <label>Menús</label><br>                          
                        <?php echo $this->Form->input('menu', array('label' => '', 'name' => 'menu', 'empty' => 'Seleccione uno', 'type' => 'select', 'options' => $menus, 'class' => 'form-control'));?>
                    </div>             
                </div>
                
                <div class="col-md-3">
                    <div class="form-group ">  
                        <label>Perfiles</label><br>                          
                        <?php echo $this->Form->input('perfles', array('label' => '', 'name' => 'perfil', 'empty' => 'Seleccione uno', 'type' => 'select', 'options' => $perfiles, 'class' => 'form-control'));?>
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
    
	<legend><h2><b><?php echo __('Menú - Perfil'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">
            <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
            <tr>
                            <th><?php echo $this->Paginator->sort('Menú'); ?></th>
                            <th><?php echo $this->Paginator->sort('Perfil'); ?></th>
                            <th class="actions"><?php echo __('Acciones'); ?></th>
            </tr>
            <?php foreach ($cloudmenusPerfiles as $cloudmenusPerfile): ?>
            <tr>
                    <td>
                            <?php echo $this->Html->link($cloudmenusPerfile['Cloudmenu']['descripcion'], array('controller' => 'cloudmenus', 'action' => 'view', $cloudmenusPerfile['Cloudmenu']['id'])); ?>
                    </td>
                    <td>
                            <?php echo $this->Html->link($cloudmenusPerfile['Perfile']['descripcion'], array('controller' => 'perfiles', 'action' => 'view', $cloudmenusPerfile['Perfile']['id'])); ?>
                    </td>
                    <td class="actions">
                        
                <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Menú-Perfil', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $cloudmenusPerfile['CloudmenusPerfile']['id']))); ?>
                <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Menú-Perfil', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $cloudmenusPerfile['CloudmenusPerfile']['id']))); ?>                   
                <?php
                echo $this->Form->postLink(                        
                  $this->Html->image('png/list-2.png', array('title' => 'Eliminar Menú-Perfil', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                  array('action' => 'delete', $cloudmenusPerfile['CloudmenusPerfile']['id']), //url
                  array('escape' => false), //el escape
                  __('Está seguro que desea eliminar el Menú - Perfil?') //la confirmacion
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
		<li><?php echo $this->Html->link(__('Nuevo Menú - Perfil'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Menús'), array('controller' => 'cloudmenus', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Menú'), array('controller' => 'cloudmenus', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Perfiles'), array('controller' => 'perfiles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Perfil'), array('controller' => 'perfiles', 'action' => 'add')); ?> </li>
	</ul>
</div>
