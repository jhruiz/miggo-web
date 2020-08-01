<?php 
    $this->layout='inicio'; 
    echo ($this->Html->script('publicidad/edit.js'));
?>
<div class="container body">
<div class="main_container">
<div class="x_panel">
<div class="publicidadmoviles form">
<?php echo $this->Form->create('Publicidadmovile', array('type' => 'file')); ?>
    <div class="x_title">
        <h2><?php echo __('Imágenes Pie de Página'); ?></h2>
    </div>  
	<fieldset>
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '11', 'id' => 'menuvert'))?> 
                <div class="row">
                    <div class="col-md-12">
                        <div>
                            <div>
                                    <?php $contador = 6; $k = 0;?>
                                    <?php for($i = 0; $i < ceil(count($pubMovil)/6); $i++){?>
                                    <div class="row">
                                        <?php for($k; $k < $contador; $k++){?>
                                            <?php if (isset($pubMovil[$k])){?>
                                            
                                                <?php $checked = $pubMovil[$k]['Publicidadmovile']['mostrar'] ? 'checked' : ''; ?>
                                                
                                                <div class="col-md-2">     
                                                    <div class="thumbnail">                                    
                                                        <div id="dv_<?php echo $pubMovil[$k]['Publicidadmovile']['id'];?>" class="">
                                                            <div class="image view view-first">
                                                                
                                                                <img    src="<?php echo $urlImg . $pubMovil[$k]['Publicidadmovile']['empresa_id'] . '/' . $pubMovil[$k]['Publicidadmovile']['url_img'];?>" 
                                                                        class="img-responsive img-rounded center-block" 
                                                                        style="max-width: 100%; max-height: 100%" />                                         
                                                            
                                                            </div>
                                                            <div class="container-fluid">
                                                                <div class="row">
                                                                    <div class="mask col-md-9">
                                                                        <input  type="checkbox" 
                                                                                class="chkPdr" 
                                                                                name="<?php echo $pubMovil[$k]['Publicidadmovile']['id'];?>" 
                                                                                id="chk_<?php echo $pubMovil[$k]['Publicidadmovile']['id'];?>" 
                                                                                value="<?php echo $pubMovil[$k]['Publicidadmovile']['id'];?>" 
                                                                                <?php echo $checked; ?>
                                                                                onclick="cambiarVisualizacion(this);"> Mostrar<br>
                                                                    </div>  
                                                                    <div class="mask col-md-3">
                                                                        <?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-trash-o fa-lg', 'title' => 'Eliminar')), array('action' => 'delete', $pubMovil[$k]['Publicidadmovile']['id'], '0'), array('escape' => false)) ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div><!--thumbnail-->
                                                </div>
                                            <?php } else{break;}?>
                                        <?php } $contador = $contador + 6;?>
                                    </div>
                                    <?php }?> 
                                    <div class="clearfix visible-sm-block"></div>
                                    <div class="form-group form-inline"> 
                                        <?php echo $this->Form->input('imagen', array('type' => 'file')); ?>
                                        <p class="help-block">Máximo 1MB</p>
                                    </div>    
                                    <?php echo $this->Form->submit('Cargar',array('class'=>'btn btn-primary'));?> 
                            </div>
                        </div>
                    </div>
                </div><br><br>
	</fieldset>
</form>
</div>
</div><!--termina panel-->
</div><!-- -->
</div><!-- -->