<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('bandeja/gestionBandejas.js'));?>
<div class="utilidades index">
    <?php echo $this->Form->create('Utilidade',array('action'=>'searchrotacion','method'=>'post', 'class' => 'form-inline'));?>
    <legend><h2><b><?php echo __('Buscar rotación de productos'); ?></b></h2></legend> 
    <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '34', 'id' => 'menuvert'))?>    
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">  
                <label>Fecha Inicial</label><br>                          
                <input name="data[Rotacion][fechaInicio]" id="fechaInicio" autocomplete="off" class="date form-control" placeholder="Fecha Inicio" type="text">                             
            </div>             
        </div>       
        <div class="col-md-3">
            <div class="form-group">
                <label>Fecha Final</label><br>                          
                <input name="data[Rotacion][fechaFin]" id="fechaFin" autocomplete="off" class="date form-control" placeholder="Fecha Fin" type="text">               
            </div>        
        </div>
        <div class="col-md-3">
            &nbsp; 
        </div>                      
        <div class="col-md-3">
            &nbsp;
        </div>                      
    </div><br>              
    <?php echo $this->Form->submit('Buscar',array('class'=>'btn btn-primary'));?>
    </form><br><br>  
    
	<legend><h2><b><?php echo __('Rotacion de productos. ' . $fechaInicio . ' - ' . $fechaFin); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">                
                <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                <tr>
                    <th><?php echo h('Producto'); ?></th>
                    <th><?php echo h('Promedio cantidad'); ?></th>
                    <th><?php echo h('Promedio precio venta'); ?></th>
                    <th><?php echo h('Promedio utilidad bruta'); ?></th>
                    <th><?php echo h('Promedio utilidad porcentual'); ?></th>
                    <th><?php echo h('Promedio costo producto'); ?></th>
                </tr>
                <?php foreach ($arrRotation as $rot): ?>
                <tr>
                    <td><?php echo h($rot['descripcion']); ?>&nbsp;</td>
                    <td class="text-right"><?php echo h(number_format(intval($rot['prom_venta']))); ?>&nbsp;</td>
                    <td class="text-right"><?php echo h("$" . number_format(intval($rot['prom_precio_venta']),2)); ?>&nbsp;</td>
                    <td class="text-right"><?php echo h("$" . number_format(intval($rot['prom_utilidad_bruta']),2)); ?>&nbsp;</td>
                    <td class="text-right"><?php echo h("%" . $rot['prom_utilidad_porc']); ?>&nbsp;</td>
                    <td class="text-right"><?php echo h("$" . number_format(intval($rot['costo_producto']),2)); ?>&nbsp;</td>
                </tr>
                <?php endforeach; ?>
                </table>
                <legend>&nbsp;</legend>
                
            </div>
        </div>
</div><br><br>
<?php echo $this->Form->create('Reporte',array( 'controller' => 'reportes','action'=>'descargarRotacion')); ?>
    <fieldset>    
        <?php echo $this->Form->input('rpfechIni', array('type' => 'hidden', 'name' => 'rpfechIni', 'value' => $fechaInicio))?>

        <?php echo $this->Form->input('rpfechFin', array('type' => 'hidden', 'name' => 'rpfechFin', 'value' => $fechaFin))?>
        <?php echo $this->Form->submit('Descargar',array('class'=>'btn btn-primary')); ?>
    </fieldset>
</form><br><br>
