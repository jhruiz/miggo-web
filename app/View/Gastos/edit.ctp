<?php echo ($this->Html->script('bandeja/gestionBandejas')); ?>
<?php echo ($this->Html->script('gastos/gasto_edit')); ?>
<?php $this->layout = 'inicio';?>

<fieldset>
    <legend>
        <h2><b><?php echo __('Editar Gasto'); ?></b></h2>
    </legend>

    <input type="hidden" id="usuarioregistra_id" value="<?php echo ($usuario_id); ?>">
    <input type="hidden" id="id" value="<?php echo ($gasto['Gasto']['id']); ?>">
    <input type="hidden" id="cuenta_id" value="<?php echo ($gasto['Gasto']['cuenta_id']); ?>">


    <div class="row">

        <div class="col-md-3">
            <label for="ClienteClasificacionclienteId">Items</label>
            <?php echo $this->Form->input("clasificacioncliente_id",
    array(
        'name' => "data[Cliente][clasificacioncliente_id]",
        'label' => "",
        'value' => $gasto['Gasto']['itemsgasto_id'],
        'id' => 'item_actual_id',
        'type' => 'select',
        'options' => $itemsGasto,
        'class' => 'form-control',
    )
);
?>
        </div>
    

        <div class="col-md-3">
            <label for="ClienteClasificacionclienteId">Usuario</label>
            <?php echo $this->Form->input("clasificacioncliente_id",
    array(
        'name' => "data[Cliente][clasificacioncliente_id]",
        'label' => "",
        'value' => $gasto['Gasto']['usuario_id'],
        'id' => 'usuario_actual_id',
        'type' => 'select',
        'options' => $usuarios,
        'class' => 'form-control',
    )
);
?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Valor Actual</label>
                <input type="text" readonly class="form-control" id="val_actual"
                    value="<?php echo (number_format($gasto['Gasto']['valor'], 0)); ?>">
            </div>
        </div>
        <div class="col-md-6">&nbsp;</div>
    </div>




    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Nuevo Valor</label>
                <input type="text" class="form-control numericPrice" id="val_nuevo" value=""
                    placeholder="Nuevo valor del gasto">
            </div>
        </div>
        <div class="col-md-6">&nbsp;</div>
    </div>
</fieldset>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Descripción:</label>
            <textarea class="form-control" rows="5"
                id="descripcion" readonly><?php echo $gasto['Gasto']['descripcion']; ?></textarea>
        </div>
    </div>
    <div class="col-md-6">&nbsp;</div>
</div>

<button type="button" class="btn btn-primary" id="preguardar">
    Guardar
</button>


<div class="modal fade" id="act_gasto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Por favor, indique los motivos por los cuales modifica el valor del gasto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label>Descripción:</label>
                <textarea class="form-control" rows="5" id="new_descripcion"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardar_gasto">Guardar</button>
            </div>
        </div>
    </div>
</div>