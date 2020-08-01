<?php
    $this->layout='inicio';
?>

<div class="usuarios index">
        <legend><h2><?php echo __('Usuarios'); ?></h2></legend>
        <table class="CSSTable">
	<tr>
			<th><?php echo __('nombre'); ?></th>
                        <th><?php echo __('identificacion'); ?></th>
			
			<th class="actions"><?php echo __('Acciones'); ?></th>
	</tr>
	<?php foreach ($arrUsuarios as $usuario): ?>
	<tr>
		<td><?php echo h($usuario['Usuario']['nombre']); ?>&nbsp;</td>    
                <td><?php echo h($usuario['Usuario']['identificacion']); ?>&nbsp;</td> 
                <td><?php echo $this->Html->link(__('Ver'), array('controller' => 'paquetesusuarios', 'action' => 'listasolicitudes', $usuario['Usuario']['id'])); ?></td>
	</tr>
<?php endforeach; ?>
	</table>

</div>
