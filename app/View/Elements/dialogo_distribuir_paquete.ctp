<script type="text/javascript">
    var urlBase = <?php echo json_encode(Router::url('/')); ?>;
    function abrirDialogoDistribuirFactura(idPaquete){
        $('#iframe_distribuir_factura').attr('src', urlBase+'paquetes/distribuirPaquete/'+idPaquete);
        
        $('#modal_distribuir_factura').modal('show');
        
    }
</script>
<div id="modal_distribuir_factura" class="modal hide fade" tabindex="-1" role="dialog" style="height: 600px;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Distribución de Montos</h3>
  </div>
  <div class="modal-body">
    <iframe id="iframe_distribuir_factura" style="width: 100%; height: 550px;"></iframe>
  </div>
  <div class="modal-footer" >
    <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
    <button class="btn btn-primary" onclick="$('#iframe_distribuir_factura').get(0).contentWindow.enviarFormulario()">Guardar</button>
  </div>
</div>
