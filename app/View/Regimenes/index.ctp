<?php $this->layout='inicio'; ?>
<div class="regimenes index">
	<legend><h2><b><?php echo __('Régimen'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                    <tr>
                                    <th><?php echo $this->Paginator->sort('Nombre'); ?></th>
                                    <th class="actions"><?php echo __('Acciones'); ?></th>
                    </tr>
                    <?php foreach ($regimenes as $regimene): ?>
                    <tr>
                            <td><?php echo h($regimene['Regimene']['descripcion']); ?>&nbsp;</td>
                            
                            <td class="actions">
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Régimen', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $regimene['Regimene']['id']))); ?>
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Régimen', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $regimene['Regimene']['id']))); ?>                   
                            <?php
                            echo $this->Form->postLink(                        
                              $this->Html->image('png/list-2.png', array('title' => 'Eliminar Régimen', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                              array('action' => 'delete', $regimene['Regimene']['id']), //url
                              array('escape' => false), //el escape
                              __('Está seguro que desea eliminar el régimen %s?', $regimene['Regimene']['descripcion']) //la confirmacion
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
		<li><?php echo $this->Html->link(__('Nuevo Régimen'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Depósitos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Deposito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
	</ul>
</div>
