<?php
/**
 * Bloque "Retenciones (informativas)" mientras se arma la factura.
 * Lo llena js/facturas/retenciones_prefactura.js. No cambia el total ni los pagos.
 */
echo $this->Html->script('facturas/retenciones_prefactura');
?>
<div id="bloqueRetenciones" class="row" style="display:none; margin-top: 10px;">
    <div class="col-md-6 col-md-offset-6">
        <div class="panel panel-default" style="margin-bottom: 0;">
            <div class="panel-heading">
                <b><i class="fa fa-balance-scale"></i> Retenciones (informativas)</b>
                <small class="text-muted">No cambian el total ni los pagos de la factura.</small>
            </div>
            <table class="table table-condensed" style="margin-bottom: 0;">
                <tbody id="retencionesFilas"></tbody>
                <tfoot>
                    <tr>
                        <th>Total retenciones</th>
                        <th class="text-right" id="retencionesTotal"></th>
                    </tr>
                    <tr>
                        <th>Neto a pagar</th>
                        <th class="text-right" id="retencionesNeto"></th>
                    </tr>
                </tfoot>
            </table>
            <div id="retencionesAvisos" class="alert alert-warning" role="status" style="display:none; margin: 10px;"></div>
        </div>
    </div>
</div>
