<?php 
$this->layout=false;
echo $this->Html->script('eventos/crearevento.js');
?>

<?php echo $this->Form->input('empresa', array(
    'id' => 'empresa_id', 
    'value' => $empresaId,
    'type' => 'hidden')
    )?>

<?php echo $this->Form->input('estado', array(
    'id' => 'estado_id', 
    'value' => $estado['Estadoalerta']['id'],
    'type' => 'hidden')
    )?>

<div class="col-md-12" style="margin-bottom: 20px;">                    
    <div class="col-md-12">
            <?php echo $this->Form->input('typeevent', array(
            'label' => 'Tipo de Evento', 
            'type' => 'select', 
            'options' => $tipoEventos, 
            'id' => 'typeevent',
            'class' => 'form-control'
            ));?>
    </div>
</div>

<div class="col-md-12" style="margin-bottom: 20px;">                    
    <div class="col-md-12">
            <?php echo $this->Form->input('users', array(
            'label' => 'Responsable', 
            'type' => 'select', 
            'options' => $usuarios, 
            'id' => 'user_id',
            'class' => 'form-control'
            ));?>
    </div>
</div>

<div class="col-md-12" style="margin-bottom: 20px;">
    <div class="col-md-12">
        <label>Fecha Evento</label><br>   
        <input class="date form-control" autocomplete="off" type="text" id="date_event">           
    </div>
</div>  

<div class="col-md-12" style="margin-bottom: 20px;">
    <div class="col-md-12">
        <label>Cliente</label><br>   
        <input class="form-control" autocomplete="off" type="text" id="client_name">           
    </div>
</div>  

<div class="col-md-12" style="margin-bottom: 20px;">
    <div class="col-md-12">
        <label>Teléfono</label><br>   
        <input class="form-control" autocomplete="off" type="text" id="client_tel">           
    </div>
</div>  

<div class="col-md-12" style="margin-bottom: 20px;">
    <div class="col-md-12">
        <label>Placa</label><br>   
        <input class="form-control" autocomplete="off" type="text" id="placa">           
    </div>
</div>  

<div class="col-md-12" style="margin-bottom: 20px;">
    <div class="col-md-12">
        <label for="obs_fact">Descripción</label>
        <textarea id="desc_event" class="md-textarea form-control" rows="3"></textarea>                            
    </div>
</div>

<div class="col-md-12" style="margin-bottom: 20px;">
    <div class="col-md-12">
    <button id="createEvent" class="btn btn-primary">Guardar</button>
    </div>
</div>