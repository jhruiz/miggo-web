<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('bandeja/gestionBandejas.js'));?>
<div class="gastos index">
        <?php echo $this->Form->create('Gastos',array('action'=>'search','method'=>'post', 'class' => 'form-inline'));?>
        <legend><h2><b><?php echo __('Buscar Fecha de Gastos'); ?></b></h2></legend>  
        <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '40', 'id' => 'menuvert'))?>    
        <div class="row">
            
            <div class="col-md-12" style="margin-bottom: 20px;">

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="fechaInicio">Fecha Inicial</label><br>
                        <input name="data[Gasto][fechaInicio]" id="fechaInicio" autocomplete="off" class="date form-control" placeholder="Fecha Inicio" type="text">
                    </div>                    
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="fechaFin">Fecha Final</label><br>
                        <input name="data[Gasto][fechaFin]" id="fechaFin" autocomplete="off" class="date form-control" placeholder="Fecha Fin" type="text">
                    </div>                    
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="GastosItems">Items Gastos</label>
                        <?php echo $this->Form->input('items', array('label' => '', 'name' => 'data[Gasto][itemsgasto_id]', 'empty' => 'Seleccione uno', 'type' => 'select', 'options' => $itemsGasto, 'class' => 'form-control'));?>
                    </div>                    
                </div>
            </div>
        </div><br>              
        <?php echo $this->Form->submit('Buscar',array('class'=>'btn btn-primary'));?>
        </form><br>
        
	<legend><h2><b><?php echo __('Gastos'); ?></b></h2></legend>
	<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '40', 'id' => 'menuvert'))?>
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                <tr>
                                <th><?php echo ('Descripcion'); ?></th>
                                <th><?php echo ('Usuario'); ?></th>
                                <th><?php echo ('Empresa'); ?></th>
                                <th><?php echo ('Fecha del Gasto'); ?></th>
                                <th><?php echo ('Fecha del Registro'); ?></th>
                                <th><?php echo ('Item del Gasto'); ?></th>                                
                                <th><?php echo ('Cuenta Origen'); ?></th>
                                <th><?php echo ('Valor'); ?></th>
                                <th><?php echo ('Acciones'); ?></th>
                </tr>
                <?php foreach ($gastos as $gasto): ?>
                <tr>
                        <td><?php echo h($gasto['descripcion']); ?>&nbsp;</td>
                        <td><?php echo h($gasto['usuario']); ?></td>
                        <td><?php echo h($gasto['empRel']); ?></td>
                        <td><?php echo h($gasto['fechagasto']); ?>&nbsp;</td>
                        <td><?php echo h($gasto['created']); ?>&nbsp;</td>
                        <td><?php echo h($gasto['itemsgasto']); ?>&nbsp;</td>                        
                        <td><?php echo h($gasto['cuenta']); ?>&nbsp;</td>
                        <td><?php echo h("$" . number_format($gasto['valor'],2)); ?>&nbsp;</td>
                        <td class="actions">                            
                            <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Gasto', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $gasto['id']))); ?>
                            <?php if(empty($gasto['traslado'])){echo $this->Html->image('png/list-12.png', array('title' => 'Editar Gasto', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $gasto['id'])));}?>                          
                        </td>                        
                </tr>
                <?php endforeach; ?>
                <tr><td colspan="6"></td>
                    <td><b>TOTAL</b></td>
                    <td><?php echo h("$" . number_format($ttalGastos,2));?></td>
                </tr>
                </table>
            </div>
        </div>
</div>
<?php echo $this->Form->create('Reporte',array( 'controller' => 'reportes','action'=>'descargarGastos')); ?>
    <fieldset>
        <?php echo $this->Form->input('rpfechaInicio', array('type' => 'hidden', 'name' => 'rpfechainicio', 'value' => $fechaInicio))?>
        <?php echo $this->Form->input('rpfechaFin', array('type' => 'hidden', 'name' => 'rpfechafin', 'value' => $fechaFin))?>
        <?php echo $this->Form->input('rpItem', array('type' => 'hidden', 'name' => 'rpitem', 'value' => $itemId))?>
        <?php echo $this->Form->submit('Descargar',array('class'=>'btn btn-primary')); ?>
    </fieldset>
</form>
<br>

<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Nuevo Gasto'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
