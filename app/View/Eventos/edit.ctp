<?php 
    $this->layout='inicio'; 
    echo $this->Html->script('eventos/edit.js');
?>
<div class="eventos form">
<?php echo $this->Form->create('Evento', array('class' => 'form-inline')); ?>
    <fieldset>
    <legend><h2><b><?php echo __('Editar Evento'); ?></b></h2></legend>
    <section class="main row">

    <?php echo $this->Form->input('id'); ?>
    <?php echo $this->Form->input('empresa_id', array('type' => 'hidden')); ?>

        <div class="col-md-12" style="margin-bottom: 20px;">

            <div class="form-group">
                <label for="">Tipo de Evento</label>
                <?php 
                    echo $this->Form->input("tipoevento_id",
                            array(
                                'label' => "",
                                'type' => 'select',
                                'options'=>$tipoEventos,
                                'class' => 'form-control',
                                'style' => 'width:300px;'
                            )
                    );
                ?>
            </div>

        </div>
        
        <div class="col-md-12" style="margin-bottom: 20px;">
            <div class="form-group">
                <label for="">Responsable</label>
                <?php 
                    echo $this->Form->input("usuario_id",
                            array(
                                'label' => "",
                                'type' => 'select',
                                'options'=>$usuarios,
                                'class' => 'form-control',
                                'style' => 'width:300px;'
                            )
                    );
                ?>
            </div>            
        </div>        
        
        <div class="col-md-12" style="margin-bottom: 20px;">
            <div class="form-group">
                <label for="">Fecha del Evento</label>
                <?php echo $this->Form->input('fecha', array(
                    'label' => '', 
                    'type' => 'text', 
                    'class' => 'date form-control', 
                    'value' => $evento['Evento']['fecha'],
                    'autocomplete' => 'off',
                    'style' => 'width:300px;')); ?>
            </div>        
                        
        </div>
        
        <div class="col-md-12" style="margin-bottom: 20px;">
            <div class="form-group">
                <label for="">Cliente</label>
                <?php echo $this->Form->input('cliente', array(
                    'label' => '', 
                    'type' => 'text', 
                    'class' => 'form-control', 
                    'value' => $evento['Evento']['cliente'],
                    'autocomplete' => 'off',
                    'style' => 'width:300px;')); ?>
            </div>                                           
        </div>
        
        <div class="col-md-12" style="margin-bottom: 20px;">   
            <div class="form-group">
                <label for="">Estado</label>
                <?php 
                    echo $this->Form->input("estadoalerta_id",
                            array(
                                'name'=>"data[Evento][estadoalerta_id]",
                                'label' => "",
                                'type' => 'select',
                                'options'=>$estado,
                                'class' => 'form-control',
                                'style' => 'width:300px;'
                            )
                    );
                ?>
            </div>                                        
        </div>
        
        
        <div class="col-md-12" style="margin-bottom: 20px;"> 
            <div class="form-group"> 
                <label for="">Teléfono</label>
                <?php echo $this->Form->input('telefono', array(
                    'label' => '', 
                    'class' => 'form-control',
                    'autocomplete' => 'off',
                    'style' => 'width:300px;'
                    )); ?>                   
            </div>  
        </div>
        
        <div class="col-md-12" style="margin-bottom: 20px;">   
            <div class="form-group"> 
                <label for="">Placa</label>
                <?php echo $this->Form->input('placa', array(
                    'label' => '', 
                    'class' => 'form-control',
                    'autocomplete' => 'off',
                    'style' => 'width:300px;'
                    )); ?>                   
            </div>                                        
        </div>  

        <div class="col-md-12" style="margin-bottom: 20px;">  
            <div class="form-group">
                <label>Descripcion</label><br>
                <textarea name="data[Evento][descripcion]" class="form-control" cols="50" rows="6" id="EventoDescripcion"><?php echo($evento['Evento']['descripcion']);?>
                </textarea>
            </div>
                
        </div>  
        
    </section>                            
                
	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div>
