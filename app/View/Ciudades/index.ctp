<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('ciudades/indexCiudades'));?>
<div class="ciudades index">
    
            <?php echo $this->Form->create('Ciudades',array('action'=>'search','method'=>'post'));?>
            <legend><h2><b><?php echo __('Buscar Ciudades'); ?></b></h2></legend>      
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group ">  
                        <label>Nombre</label><br>                          
                        <input name="nombre" id="nombre" class="form-control" placeholder="Nombre de la Ciudad" type="text">
                    </div>             
                </div>                      
                
                <div class="col-md-3">
                    <div class="form-group ">  
                        <?php echo $this->Form->input('pais', array('label' => 'Pais', 'name' => 'pais', 'empty' => 'Seleccione uno', 'type' => 'select', 'options' => $paises, 'class' => 'form-control'));?>
                    </div>             
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
        
        
	<legend><h2><b><?php echo __('Ciudades'); ?></b></h2></legend>
        <div class="">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                    <tr>
                                    <th><?php echo $this->Paginator->sort('descripcion', 'Nombre'); ?></th>
                                    <th><?php echo $this->Paginator->sort('paise_id', 'País'); ?></th>
                                    <th class="actions"><?php echo __('Acciones'); ?></th>
                    </tr>
                    <?php foreach ($ciudades as $ciudade): ?>
                    <tr>
                            <td><?php echo h($ciudade['Ciudade']['descripcion']); ?>&nbsp;</td>
                            <td>
                                    <?php echo $this->Html->link($ciudade['Paise']['descripcion'], array('controller' => 'paises', 'action' => 'view', $ciudade['Paise']['id'])); ?>
                            </td>
                            <td class="actions">
                                <?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-eye fa-lg', 'title' => 'Ver Ciudad')), array('action' => 'view', $ciudade['Ciudade']['id']), array('escape' => false)) ?>
                                <?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-pencil fa-lg', 'title' => 'Editar Ciudad')), array('action' => 'edit', $ciudade['Ciudade']['id']), array('escape' => false)) ?>
                                <?php 
                                    echo $this->Form->postLink($this->Html->tag('i', '', array(
                                        'class' => 'fa fa-trash-o fa-lg', 
                                        'title' => 'Eliminar Producto',
                                        'style' => 'color:red;')
                                    ), 
                                    array(
                                        'action' => 'delete', $ciudade['Ciudade']['id']
                                    ), 
                                    array('escape' => false),
                                    __('Está seguro que desea eliminar el producto %s?', $ciudade['Ciudade']['descripcion'])
                                    );
                                ?>                                    
                            </td>                            
                    </tr>
                    <?php endforeach; ?>
                </table>
                <?php echo $this->Form->create('Reporte',array( 'controller' => 'reportes','action'=>'descargarListaCiudades')); ?>
                    <fieldset>
                                <td colspan="2"><?php echo $this->Form->submit('Generar Reporte',array('class'=>'btn btn-info')); ?></td>                
                    </fieldset>
                </form>
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
		<li><?php echo $this->Html->link(__('Nueva Ciudad'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Paises'), array('controller' => 'paises', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Pais'), array('controller' => 'paises', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista de Depósitos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Depósito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
	</ul>
</div>
