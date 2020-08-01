<?php $this->layout='inicio'; ?>
<div class="reteicaretefuentes form">
<?php echo $this->Form->create('Reteicaretefuente', array('class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Agregar Reteica - Retefuente'); ?></b></h2></legend>
                <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'label' => 'Usuario', 'value' => $empresaId)); ?>
                
                <div class="row"> 
                    <div class="form-group">
                        <label for="ReteicaretefuenteDescripcion">Nombre</label>
                        <?php echo $this->Form->input('descripcion', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre')); ?>
                    </div>
                </div><br>            
                
                <div class="row"> 
                    <div class="form-group">
                        <label for="ReteicaretefuentePorcentaje">Porcentaje</label>
                        <?php echo $this->Form->input('porcentaje', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Porcentaje')); ?>
                    </div>
                </div><br>    
                
                <div class="row container-fluid">
                    <div class="row">
                        <div class="checkbox">
                            <label><input type="checkbox" name="data[Reteicaretefuente][reteica]"><b>Reteica</b></label>
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="checkbox">
                            <label><input type="checkbox" name="data[Reteicaretefuente][retefuente]"><b>Retefuente</b></label>
                        </div>
                    </div>
                </div>
	</fieldset><br>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div><br>
<div class="actions">
    <legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Categorías Compras'), array('action' => 'index')); ?></li>
	</ul>
</div>
