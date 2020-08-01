<?php $this->layout='inicio'; ?>
<div class="licenciasEmpresas index">
	<legend><h2><b><?php echo __('Empresas - Licencias'); ?></b></h2></legend>
        <div class="container">
            <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                            <th><?php echo $this->Paginator->sort('fechainicio', 'Inicio de la Licencia'); ?></th>
                            <th><?php echo $this->Paginator->sort('fechafin', 'Fin de la Licencia'); ?></th>
                            <th><?php echo $this->Paginator->sort('licencia_id'); ?></th>
                            <th><?php echo $this->Paginator->sort('empresa_id'); ?></th>
                            <th><?php echo $this->Paginator->sort('estado_id', 'Estado de la Licencia'); ?></th>
                            <th><?php echo $this->Paginator->sort('codigo', 'Código de la Licencia'); ?></th>
                            <th><?php echo $this->Paginator->sort('cantidad', 'Cantidad de Licencias'); ?></th>
                            <th class="actions"><?php echo __('Acciones'); ?></th>
            </tr>
            <?php foreach ($licenciasEmpresas as $licenciasEmpresa): ?>
            <tr>
                    <td><?php echo h($licenciasEmpresa['LicenciasEmpresa']['fechainicio']); ?>&nbsp;</td>
                    <td><?php echo h($licenciasEmpresa['LicenciasEmpresa']['fechafin']); ?>&nbsp;</td>
                    <td>
                            <?php echo $this->Html->link($licenciasEmpresa['Licencia']['cantidaddias'], array('controller' => 'licencias', 'action' => 'view', $licenciasEmpresa['Licencia']['id'])); ?>
                    </td>
                    <td>
                            <?php echo $this->Html->link($licenciasEmpresa['Empresa']['nombre'], array('controller' => 'empresas', 'action' => 'view', $licenciasEmpresa['Empresa']['id'])); ?>
                    </td>
                    <td>
                            <?php echo $this->Html->link($licenciasEmpresa['Estado']['descripcion'], array('controller' => 'estados', 'action' => 'view', $licenciasEmpresa['Estado']['id'])); ?>
                    </td>
                    <td><?php echo h($licenciasEmpresa['LicenciasEmpresa']['codigo']); ?>&nbsp;</td>
                    <td><?php echo h($licenciasEmpresa['LicenciasEmpresa']['cantidad']); ?>&nbsp;</td>
                    <td class="actions">
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Licencia de la Empresa', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $licenciasEmpresa['LicenciasEmpresa']['id']))); ?>
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Licencia de la Empresa', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $licenciasEmpresa['LicenciasEmpresa']['id']))); ?>                   
                            <?php
                            echo $this->Form->postLink(                        
                              $this->Html->image('png/list-2.png', array('title' => 'Eliminar Licencia del Usuario', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                              array('action' => 'delete', $licenciasEmpresa['LicenciasEmpresa']['id']), //url
                              array('escape' => false), //el escape
                              __('Está seguro que desea eliminar la licencia para la empresa?') //la confirmacion
                            ); 
                            ?> 
                    </td>
            </tr>
    <?php endforeach; ?>
            </table>
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
		<li><?php echo $this->Html->link(__('Nueva Licencias - Empresa'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Licencias'), array('controller' => 'licencias', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Licencia'), array('controller' => 'licencias', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Empresas'), array('controller' => 'empresas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Empresa'), array('controller' => 'empresas', 'action' => 'add')); ?> </li>
	</ul>
</div>
