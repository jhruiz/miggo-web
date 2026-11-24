<?php
/**
 * Pestañas de Gestión de impuestos: Impuestos / Retenciones en ventas.
 * Variable: $activa = 'impuestos' | 'retenciones'
 */
$activa = isset($activa) ? $activa : 'impuestos';
?>
<ul class="nav nav-tabs" style="margin-bottom: 15px;">
    <li class="<?php echo $activa === 'impuestos' ? 'active' : ''; ?>">
        <?php echo $this->Html->link(__('Impuestos'), array('controller' => 'impuestos', 'action' => 'index')); ?>
    </li>
    <li class="<?php echo $activa === 'retenciones' ? 'active' : ''; ?>">
        <?php echo $this->Html->link(__('Retenciones en ventas'), array('controller' => 'impuestos', 'action' => 'retenciones')); ?>
    </li>
</ul>
