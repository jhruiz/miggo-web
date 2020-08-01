<?php
    $this->layout='inicio'; 
?>
<div class="categoriacompras index container-fluid">

            <?php echo $this->Form->create('Categoriacompra',array('action'=>'search','method'=>'post', 'class' => 'form-inline'));?>
            <legend><h2><b><?php echo __('Buscar Categoría de Compra'); ?></b></h2></legend>      
            <div class="row">
                <?php echo $this->Form->input('empresa', array('type' => 'hidden', 'value' => $empresaId, 'id' => 'empresa_id'))?>
                
                <div class="row">
                    <div class="form-group">  
                        <label for="buscarcategoria">Categoría de compra</label><br>                          
                        <?php echo $this->Form->input('buscarcategoria', array('label' => false, 'id' => 'buscarcategoria', 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Nombre de la categoría')); ?>
                    </div>             
                </div>
        </div><br>        
        <div class="form-group ">  
        <?php echo $this->Form->submit('Buscar',array('class'=>'btn btn-primary'));?>                
        </div>                      

        </form>             
        
    <legend><h2><b><?php echo __('Categorías de Compras'); ?></b></h2></legend>
	
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                    <th><?php echo h('Nombre'); ?></th>
                    <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                
                <?php foreach ($categoriacompras as $categoriacompra): ?>
                <tr>                    
                    <td>
                            <?php echo h($categoriacompra['Categoriacompra']['descripcion']); ?>
                    </td>
                    <td class="actions">
                        <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Categoría', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $categoriacompra['Categoriacompra']['id']))); ?>
                        <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Categoría', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $categoriacompra['Categoriacompra']['id']))); ?>                   
                    </td>                        
                        
                </tr>
                <?php endforeach; ?>

                </table>
                <legend>&nbsp;</legend>                              
                
            </div>
        </div>        
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Nueva Categoría'), array('controller' => 'categoriacompras', 'action' => 'add')); ?> </li>
	</ul>
</div>
</form>
<br>
