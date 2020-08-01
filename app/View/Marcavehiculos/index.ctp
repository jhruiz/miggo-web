<?php $this->layout='inicio'; ?>
<div class="marcavehiculos index">
    
            <?php echo $this->Form->create('Marcavehiculos',array('action'=>'search','method'=>'post'));?>
            <legend><h2><b><?php echo __('Buscar Marcas de Vehículo'); ?></b></h2></legend>      
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group ">  
                        <label>Nombre</label><br>                          
                        <input name="nombre" id="nombre" class="form-control" placeholder="Nombre Marca" type="text">
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
            
	<legend><h2><b><?php echo __('Marcas de Vehículo'); ?></b></h2></legend>
        
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                                <th><?php echo $this->Paginator->sort('descripcion', 'Nombre Marca'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($marcavehiculos as $marcavehiculo): ?>
                <tr>
                        <td><?php echo h($marcavehiculo['Marcavehiculo']['descripcion']); ?>&nbsp;</td>
                        <td class="actions">
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Marca', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $marcavehiculo['Marcavehiculo']['id']))); ?>
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Marca', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $marcavehiculo['Marcavehiculo']['id']))); ?>
                            <?php
                            echo $this->Form->postLink(                        
                              $this->Html->image('png/list-2.png', array('title' => 'Eliminar Marca', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                              array('action' => 'delete', $marcavehiculo['Marcavehiculo']['id']), //url
                              array('escape' => false), //el escape
                              __('Está seguro que desea eliminar la Marca %s?', $marcavehiculo['Marcavehiculo']['descripcion']) //la confirmacion
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
		<li><?php echo $this->Html->link(__('Nueva Marca'), array('action' => 'add')); ?></li>
	</ul>
</div>
