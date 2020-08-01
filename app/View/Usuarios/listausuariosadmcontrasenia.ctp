<?php
    $this->layout='inicio';
?>
<div class="usuarios index">
	<h3><?php echo 'Buscar Usuarios'; ?></h3>
      <?php echo $this->Form->create('Usuario',array('action'=>'searchAdmContrasenia'));?>
                <fieldset>
                        <legend><?php __('Usuario Search');?></legend>			
                        <table>
                            <tr>
                                <td>
                                    <?php echo $this->Form->input('Search.Nombre'); ?>
                                </td>
                                <td>
                                    &nbsp;&nbsp;&nbsp;
                                </td>
                                <td>
                                    <?php echo $this->Form->input('Search.Identificacion'); ?>
                                    <?php echo $this->Form->input('accion_anterior', array('type'=>'hidden', 'value'=>'listausuariosadmcontrasenia', 'id'=>'accion_anterior')); ?>
                                </td>
                            </tr>
                        </table>
                </fieldset>
        <?php echo $this->Form->submit('Buscar',array('class'=>'btn btn-info'));?>
        </form>
        <h2><?php echo __('Usuarios'); ?></h2>
	<table class="CSSTable">
	<tr>			
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('username','Login'); ?></th>
                        <th><?php echo $this->Paginator->sort('numero_identificacion','Identificacion'); ?></th>
			<th class="actions"><?php echo __('Acciones'); ?></th>
	</tr>
	<?php foreach ($usuarios as $usuario): ?>
	<tr>		
		<td><?php echo h($usuario['Usuario']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($usuario['Usuario']['username']); ?>&nbsp;</td>
                <td><?php echo h($usuario['Usuario']['identificacion']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Cambiar Contraseña'), array('action' => 'admcontrasenia', $usuario['Usuario']['id'])); ?>
		</td>   
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Pagina {:page} de {:pages}, mostrando {:current} registros de {:count} en total, a partir del registro {:start}, finalizando en {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('Anterior '), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ' | '));
		echo $this->Paginator->next(__(' Siguiente') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
