<?php
/**
 * Filas de retenciones informativas (snapshot) debajo del total de la factura.
 * Se usa dentro de una <table> ya existente: en la factura y en el ticket.
 * Si la factura no tiene retenciones guardadas, no pinta nada.
 *
 * Variables:
 *   $retenciones   resultado de FacturaRetencion::obtenerResumenPorTributo()
 *   $totalFactura  total de la factura (para el neto si no está guardado)
 *   $netoGuardado  facturas.neto_a_pagar (null si no se guardó)
 *   $colspan       columnas de la etiqueta (factura: 9; ticket: 1)
 *   $ticket        true en el ticket (formato compacto)
 */
if (empty($retenciones['lineas'])) {
    return;
}
$colspan = isset($colspan) ? (int) $colspan : 1;
$ticket = !empty($ticket);
$neto = ($netoGuardado !== null && $netoGuardado !== '') ? (float) $netoGuardado : ((float) $totalFactura - $retenciones['total']);
$prefijo = $ticket ? '' : '$ ';
?>
<?php foreach ($retenciones['lineas'] as $r): ?>
    <tr>
        <td align="right" <?php echo $colspan > 1 ? 'colspan="' . $colspan . '"' : ''; ?>>Menos <?php echo h($r['etiqueta']); ?> (informativa)<?php echo $ticket ? ':' : ''; ?></td>
        <td align="right">-<?php echo $prefijo . number_format($r['valor'], 2); ?></td>
    </tr>
<?php endforeach; ?>
<tr<?php echo $ticket ? ' style="font-size: 14px;"' : ''; ?>>
    <td align="right" <?php echo $colspan > 1 ? 'colspan="' . $colspan . '"' : ''; ?>><b><?php echo $ticket ? 'NETO A PAGAR:' : 'Neto a pagar'; ?></b></td>
    <td align="right"><b><?php echo ($ticket ? '$' : '$ ') . number_format($neto, 2); ?></b></td>
</tr>
