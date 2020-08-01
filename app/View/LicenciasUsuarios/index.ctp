<?php $this->layout='inicio'; ?>
<div class="licenciasUsuarios index">
	<legend><h2><b><?php echo __('Usuarios - Licencias'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                                <th><?php echo $this->Paginator->sort('fechainicio', 'Inicio de la Licencia'); ?></th>
                                <th><?php echo $this->Paginator->sort('fechafin', 'Fin de la Licencia'); ?></th>
                                <th><?php echo $this->Paginator->sort('licencia_id'); ?></th>
                                <th><?php echo $this->Paginator->sort('usuario_id'); ?></th>
                                <th><?php echo $this->Paginator->sort('estado_id', 'Estado de la Licencia'); ?></th>
                                <th><?php echo $this->Paginator->sort('codigo', 'Código de la Licencia'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($licenciasUsuarios as $licenciasUsuario): ?>
                <tr>
                        <td><?php echo h($licenciasUsuario['LicenciasUsuario']['fechainicio']); ?>&nbsp;</td>
                        <td><?php echo h($licenciasUsuario['LicenciasUsuario']['fechafin']); ?>&nbsp;</td>
                        <td>
                                <?php echo $this->Html->link($licenciasUsuario['Licencia']['cantidaddias'], array('controller' => 'licencias', 'action' => 'view', $licenciasUsuario['Licencia']['id'])); ?>
                        </td>
                        <td>
                                <?php echo $this->Html->link($licenciasUsuario['Usuario']['nombre'], array('controller' => 'usuarios', 'action' => 'view', $licenciasUsuario['Usuario']['id'])); ?>
                        </td>
                        <td>
                                <?php echo $this->Html->link($licenciasUsuario['Estado']['descripcion'], array('controller' => 'estados', 'action' => 'view', $licenciasUsuario['Estado']['id'])); ?>
                        </td>
                        <td><?php echo h($licenciasUsuario['LicenciasUsuario']['codigo']); ?>&nbsp;</td>
                        <td class="actions">
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Licencia del Usuario', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $licenciasUsuario['LicenciasUsuario']['id']))); ?>
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Licencia del Usuario', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $licenciasUsuario['LicenciasUsuario']['id']))); ?>                   
                            <?php
                            echo $this->Form->postLink(                        
                              $this->Html->image('png/list-2.png', array('title' => 'Eliminar Licencia del Usuario', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                              array('action' => 'delete', $licenciasUsuario['LicenciasUsuario']['id']), //url
                              array('escape' => false), //el escape
                              __('Está seguro que desea eliminar el producto?') //la confirmacion
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
		<li><?php echo $this->Html->link(__('Nueva Licencias para Usuario'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Licencias'), array('controller' => 'licencias', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Licencia'), array('controller' => 'licencias', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
