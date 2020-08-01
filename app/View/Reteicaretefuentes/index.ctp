<?php
    $this->layout='inicio'; 
?>
<div class="reteicaretefuentes index container-fluid">

            <?php echo $this->Form->create('Reteicaretefuente',array('action'=>'search','method'=>'post', 'class' => 'form-inline'));?>
            <legend><h2><b><?php echo __('Buscar Reteica - Retefuente'); ?></b></h2></legend>      
            <div class="row">
                <?php echo $this->Form->input('empresa', array('type' => 'hidden', 'value' => $empresaId, 'id' => 'empresa_id'))?>
                
                <div class="row">
                    <div class="form-group">  
                        <label for="reteicaretefuente">Nombre</label><br>                          
                        <?php echo $this->Form->input('reteicaretefuente', array('label' => false, 'type' => 'text', 'id' => 'reteicaretefuente', 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Nombre')); ?>
                    </div>             
                </div>
        </div><br>        
        <div class="form-group ">  
        <?php echo $this->Form->submit('Buscar',array('class'=>'btn btn-primary'));?>                
        </div>                      

        </form>             
        
    <legend><h2><b><?php echo __('Lista Reteica - Retefuente'); ?></b></h2></legend>
	
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                    <th><?php echo h('Nombre'); ?></th>
                    <th><?php echo h('Porcentaje'); ?></th>
                    <th><?php echo h('Tipo'); ?></th>
                    <th><?php echo h('Fecha'); ?></th>
                    <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                
                <?php foreach ($reteicaretefuentes as $ricarfte): ?>
                <tr>                    
                    <td>
                            <?php echo h($ricarfte['Reteicaretefuente']['descripcion']); ?>
                    </td>
                    <td>
                            <?php echo h($ricarfte['Reteicaretefuente']['porcentaje'] . '%'); ?>
                    </td>
                    <td>
                            <?php echo $ricarfte['Reteicaretefuente']['reteica'] == '1' ? h("Reteica") : h("Retefuente"); ?>
                    </td>
                    <td>
                            <?php echo h($ricarfte['Reteicaretefuente']['created']); ?>
                    </td>
                    <td class="actions">
                        <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $ricarfte['Reteicaretefuente']['id']))); ?>
                        <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $ricarfte['Reteicaretefuente']['id']))); ?>                   
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
		<li><?php echo $this->Html->link(__('Nuevo Reteica - Retefuente'), array('controller' => 'reteicaretefuentes', 'action' => 'add')); ?> </li>
	</ul>
</div>
</form>
<br>
