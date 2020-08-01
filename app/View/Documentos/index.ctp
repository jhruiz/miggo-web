<?php $this->layout='inicio'; ?>
<div class="documentos index">
    
            <?php echo $this->Form->create('Documentos',array('action'=>'search','method'=>'post', 'class' => 'form-inline'));?>
            <legend><h2><b><?php echo __('Buscar Documentos'); ?></b></h2></legend> 
            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '28', 'id' => 'menuvert'))?>     
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group ">  
                        <label>Código</label><br>                          
                        <input name="codigo" id="codigo" class="form-control" placeholder="Código del Producto" type="text">
                    </div>             
                </div>       
                
                <div class="col-md-3">
                    <div class="form-group ">  
                        <label>Tipos</label><br>                          
                        <?php echo $this->Form->input('tipodocumento', array('label' => '', 'name' => 'tipodocumento', 'empty' => 'Seleccione uno', 'type' => 'select', 'options' => $tipoDocs, 'class' => 'form-control'));?>
                    </div>             
                </div>

                <div class="col-md-6">
                    &nbsp;
                </div>                             
        </div><br>        
        <div class="row">
            <div class="col-md-3">
                <div class="form-group ">  
                <?php echo $this->Form->submit('Buscar',array('class'=>'btn btn-primary'));?>                
                </div>             
            </div>
            <div class="col-md-9">
                &nbsp;
            </div>
        </div>            

        </form><br><br>  
        
	<legend><h2><b><?php echo __('Documentos'); ?></b></h2></legend>
        
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                <tr>
                    <th><?php echo $this->Paginator->sort('codigo', 'Código'); ?></th>
                    <th><?php echo $this->Paginator->sort('tiposdocumento_id', 'Tipo Documento'); ?></th> 
                    <th><?php echo $this->Paginator->sort('created', 'Fecha'); ?></th> 
                    <th><?php echo $this->Paginator->sort('usuario_id'); ?></th>
                    <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($documentos as $documento): ?>
                <tr>
                        <td><?php echo h($documento['Documento']['codigo']); ?>&nbsp;</td>                    
                        <td><?php echo h($documento['Tiposdocumento']['descripcion']); ?>&nbsp;</td>
                        <td><?php echo h($documento['Documento']['created']); ?>&nbsp;</td>
                        <td>
                                <?php echo $this->Html->link($documento['Usuario']['nombre'], array('controller' => 'usuarios', 'action' => 'view', $documento['Usuario']['id'])); ?>
                        </td>
                        <td class="actions">
                                <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Detalle Documento', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $documento['Documento']['id']))); ?>
                        </td>
                </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>
        
        <p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Página {:page} de {:pages}, mostrando {:current} registro de {:count} en total, iniciando en registro {:start}, finalizando en {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php echo $this->Paginator->prev('< ' . __('Anterior '), array(), null, array('class' => 'prev disabled')); ?>
	<?php echo $this->Paginator->numbers(array('separator' => ' || ')); ?>
	<?php echo $this->Paginator->next(__(' Siguiente') . ' >', array(), null, array('class' => 'next disabled')); ?>
	</div>
</div>