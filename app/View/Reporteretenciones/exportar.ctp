<?php
/**
 * Exportación a Excel de los reportes de retenciones (layout export_xls).
 * Variables: $titulo, $titulos (encabezados), $filas (arreglos de valores simples).
 */
?>
<style type="text/css">
.tableTd { border: solid 0.5pt; font-family: Arial, Verdana; font-size: 13px; font-weight: bold; text-align: center; }
.tableTdContent { border: solid 0.5pt; }
</style>
<table>
    <tr><td><b><?php echo h($titulo); ?></b></td></tr>
    <tr><td><b>Fecha:</b></td><td><?php echo h(date('d/m/Y, g:i a')); ?></td></tr>
    <tr><td><b>Número de filas:</b></td><td><?php echo max(0, count($filas) - 1); ?></td></tr>
    <tr><td></td></tr>
    <tr>
        <?php foreach ($titulos as $t): ?>
            <td class="tableTd"><?php echo h($t); ?></td>
        <?php endforeach; ?>
    </tr>
    <?php foreach ($filas as $fila): ?>
        <tr>
            <?php foreach ($fila as $valor): ?>
                <td class="tableTdContent"><?php echo h($valor); ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>
