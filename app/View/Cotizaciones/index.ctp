<?php $this->layout = 'inicio';?>
<div class="cotizaciones index">
    <legend><h2><b><?php echo __('Cotizaciones'); ?></b></h2></legend>
     <!--Enlaces de acción -->
	 <div class="actions">
        <button type="button" class="btn btn-primary">
            <?php echo $this->Html->link(__('Nueva Cotizacion'), array('action' => 'add'), ["style" => "color:white;"]); ?>
        </button>
	</div>
	<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '48', 'id' => 'menuvert')) ?>
        <div class="table-responsive">
            <div class="container">
                <table cellpadding="0" cellspacing="0" class="table">
                <tr>
                                <th><?php echo __('Usuario'); ?></th>
                                <th><?php echo __('Fecha Cotizacion'); ?></th>
                                <th><?php echo __('Cliente'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($arrCot as $cot): ?>
                <tr>
                    <td><?php echo h($cot['U']['nombre']); ?>&nbsp;</td>
                    <td><?php echo h($cot['Cotizacione']['created']); ?>&nbsp;</td>
                    <td><?php echo h(!empty($cot['Cotizacione']['cliente_id']) ? $arrCli[$cot['Cotizacione']['cliente_id']] : $cot['Cotizacione']['nombre_cliente']); ?>&nbsp;</td>
                    <td class="actions">
                        <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Cotización', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $cot['Cotizacione']['id']))); ?>
                        <?php
echo $this->Form->postLink(
    $this->Html->image('png/list-2.png', array('title' => 'Eliminar Cotización', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
    array('action' => 'delete', $cot['Cotizacione']['id']), //url
    array('escape' => false), //el escape
    __('Está seguro que desea eliminar la cotización?') //la confirmacion
);
?>
                    </td>
                </tr>
                <?php endforeach;?>
                </table>
            </div>
        </div>
</div>