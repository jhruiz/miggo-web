<?php $this->layout=false; ?>
<div class="form-group">
  <label for="comment">Nota: </label>
  <textarea class="form-control" rows="5" id="notaCargue"></textarea>
</div>
<button name="<?php echo $usuarioId; ?>" id="gaurdarInventario_<?php echo $usuarioId;?>" class="btn btn-primary center-block" onclick="guardarCargueInventario(this);">Guardar</button>    


