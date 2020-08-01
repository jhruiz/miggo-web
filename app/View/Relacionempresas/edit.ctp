<?php $this->layout='inicio'; ?>
<div class="relacionempresas form">
<?php echo $this->Form->create('Relacionempresa', array('type' => 'file', 'class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Editar Empresa Relacionada'); ?></b></h2></legend>  
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '37', 'id' => 'menuvert'))?> 
                <?php echo $this->Form->input('id'); ?>
                <?php echo $this->Form->input('empresa_id', array('type' => 'hidden'));?>
                
            <div class="col-md-12" style="margin-bottom: 20px;">
                
                <div class="form-group col-md-3">
                    <label for="RelacionempresaNombre">Nombre</label>
                    <?php echo $this->Form->input('nombre', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre Empresa')); ?>
                </div>

                <div class="form-inline col-md-3">
                    <label for="RelacionempresaNit">Nit</label>
                    <?php echo $this->Form->input('nit', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Nit Empresa')); ?>
                </div>             

                <div class="form-inline col-md-3">
                    <label for="RelacionempresaDireccion">Dirección</label>
                    <?php echo $this->Form->input('direccion', array('label' => '','class' => 'form-control', 'placeholder' => 'Dirección Empresa')); ?>
                </div>                         
                
            </div>   
                
            <div class="col-md-12" style="margin-bottom: 20px;">
                
                <div class="form-inline col-md-3">
                    <label for="RelacionempresaTelefono1">Teléfono</label>
                    <?php echo $this->Form->input('telefono1', array('label' => '','class' => 'form-control', 'placeholder' => 'Teléfono Empresa')); ?>
                </div>                              

                <div class="form-inline col-md-3">
                    <label for="RelacionempresaEmail">Email</label>
                    <?php echo $this->Form->input('email', array('label' => '', 'class' => 'form-control', 'placeholder' => 'E-mail Empresa')); ?>
                </div>                        

                <div class="form-inline col-md-3">
                    <label for="RelacionempresaRepresentantelegal">Representante</label>
                    <?php echo $this->Form->input('representantelegal', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Representante Empresa')); ?>
                </div>                      
                
            </div>                
                  
                        
            <div class="col-md-12" style="margin-bottom: 20px;">
                
                <div class="form-group col-md-3">
                    <label for="RelacionempresaCodigo">Código</label>
                    <?php echo $this->Form->input('codigo', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Código de la Empresa')); ?>
                </div>                

                <div class="form-inline col-md-3"> 
                    <?php echo $this->Form->input('imagen', array('type' => 'file')); ?>
                    <p class="help-block">Máximo 1MB</p>
                </div>              
                
            </div>                               

	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Empresas Relacionadas'), array('action' => 'index')); ?></li>
	</ul>
</div>
