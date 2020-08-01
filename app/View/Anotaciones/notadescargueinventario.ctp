<?php $this->layout=false; ?>
<div class="form-group">
  <label for="comment">Nota: </label>
  <textarea class="form-control" rows="5" id="anotacionDescargue"></textarea>
</div>
<button name="<?php echo $usuarioId; ?>" id="gaurdarDescargue_<?php echo $usuarioId;?>" class="btn btn-primary center-block" onclick="guardarDescargueInventario(this);">Guardar</button>    


