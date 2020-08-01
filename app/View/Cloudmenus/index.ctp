<?php $this->layout='inicio'; ?>
<div class="cloudmenus index">
    
        <?php echo $this->Form->create('Cloudmenu',array('action'=>'search','method'=>'post'));?>
        <legend><h2><b><?php echo __('Buscar Menú'); ?></b></h2></legend>      
        <div class="row">
            <div class="col-md-3">
                <div class="form-group ">  
                    <label>Nombre</label><br>                          
                    <input name="nombre" id="nombre" class="form-control" placeholder="Nombre del Menú" type="text">
                </div>             
            </div>       
            <div class="col-md-9">
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
    
	<legend><h2><b><?php echo __('Menús'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">
            <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
            <tr>
                            <th><?php echo $this->Paginator->sort('Nombre'); ?></th>
                            <th><?php echo $this->Paginator->sort('orden'); ?></th>
                            <th class="actions"><?php echo __('Acciones'); ?></th>
            </tr>
            <?php foreach ($cloudmenus as $cloudmenu): ?>
            <tr>
                    <td><?php echo h($cloudmenu['Cloudmenu']['descripcion']); ?>&nbsp;</td>

                    <td><?php echo h($cloudmenu['Cloudmenu']['orden']); ?>&nbsp;</td>
                    <td class="actions">
                        <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Menú', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $cloudmenu['Cloudmenu']['id']))); ?>
                        <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Menú', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $cloudmenu['Cloudmenu']['id']))); ?>                   
                        <?php
                        echo $this->Form->postLink(                        
                          $this->Html->image('png/list-2.png', array('title' => 'Eliminar Menu', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                          array('action' => 'delete', $cloudmenu['Cloudmenu']['id']), //url
                          array('escape' => false), //el escape
                          __('Está seguro que desea eliminar el menú %s?', $cloudmenu['Cloudmenu']['descripcion']) //la confirmacion
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
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Nuevo Menú'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Perfiles'), array('controller' => 'perfiles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Perfil'), array('controller' => 'perfiles', 'action' => 'add')); ?> </li>
	</ul>
</div>
