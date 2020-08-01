<?php $this->layout='inicio'; ?>
<div class="depositosUsuarios index">
    
            <?php echo $this->Form->create('DepositosUsuarios',array('action'=>'search','method'=>'post'));?>
            <legend><h2><b><?php echo __('Buscar Depósitos - Usuarios'); ?></b></h2></legend>      
            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '17', 'id' => 'menuvert'))?>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group ">  
                        <label>Usuarios</label><br>                          
                        <?php echo $this->Form->input('usuarios', array('label' => '', 'name' => 'usuarios', 'empty' => 'Seleccione uno', 'type' => 'select', 'options' => $usuarios, 'class' => 'form-control'));?>
                    </div>             
                </div>
                
                <div class="col-md-3">
                    <div class="form-group ">  
                        <label>Depósitos</label><br>                          
                        <?php echo $this->Form->input('deposito', array('label' => '', 'name' => 'depositos', 'empty' => 'Seleccione uno', 'type' => 'select', 'options' => $depositos, 'class' => 'form-control'));?>
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
            
	<legend><h2><b><?php echo __('Usuarios - Depósitos'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                    <th><?php echo $this->Paginator->sort('usuario_id'); ?></th>
                    <th><?php echo $this->Paginator->sort('deposito_id'); ?></th>                                
                    <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($depositosUsuarios as $depositosUsuario): ?>                
                <tr>
                        <td><?php echo h($depositosUsuario['Usuario']['nombre']); ?>&nbsp;</td>
                        <td><?php echo h($depositosUsuario['Deposito']['descripcion']); ?>&nbsp;</td>
                        <td class="actions">
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Relación Usuario - Depósito', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $depositosUsuario['DepositosUsuario']['id']))); ?>
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Relación Usuario - Depósito', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $depositosUsuario['DepositosUsuario']['id']))); ?>                   
                            <?php
                            echo $this->Form->postLink(                        
                              $this->Html->image('png/list-2.png', array('title' => 'Eliminar Relación Usuario - Depósito', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                              array('action' => 'delete', $depositosUsuario['DepositosUsuario']['id']), //url
                              array('escape' => false), //el escape
                              __('Está seguro que desea eliminar la relación Usuario - Depósito?') //la confirmacion
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
		<li><?php echo $this->Html->link(__('Nuevo Usuario - Depósito'), array('action' => 'add')); ?></li>
	</ul>
</div>
