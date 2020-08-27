<?php $this->layout='inicio'; ?>
<div class="eventos index">
    
    <?php echo $this->Form->create('Evento', array('action' => 'index', 'method' => 'post')); ?>
            <legend><h2><b><?php echo __('Buscar Eventos'); ?></b></h2></legend>
           
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group ">
                        <?php echo $this->Form->input('producto', array('label' => 'Responsable', 'name' => 'responsable', 'placeholder' => 'Nombre del Responsable',  'class' => 'form-control')); ?>                      
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group ">
                        <?php echo $this->Form->input('producto', array('label' => 'Tipo evento', 'name' => 'responsable', 'empty' => 'Seleccione uno', 'options' => $tipoEventos, 'class' => 'form-control')); ?>
                      
                    </div>
                </div>
                <!-- <div class="col-md-2">
                        <div class="form-group">
                            <label for="fechafactura">Fecha Inicio</label><br>
                            <input name="fechafactura" id="fechafactura" autocomplete="off" class="date form-control" placeholder="Fecha de Expedición" type="date">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fechafactura_fin">Fecha Fin</label><br>
                            <input name="fechafactura_fin" id="fechafactura_fin" autocomplete="off" class="date form-control" placeholder="Fecha de Expedición" type="text">
                        </div>
                    </div> -->


        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group ">
                <?php echo $this->Form->submit('Buscar', array('class' => 'btn btn-primary')); ?>
                </div>
            </div>
            <div class="col-md-9">
                &nbsp;
            </div>
        </div>

        </form>

  <!-- Enlace tabs-->
  <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">

        <!--
        <//?php foreach ($estadoAlertas as $estadoAlertas): ?>
            <li role="presentation" class="active"><a href="/alertaordenes/index/estadoalerta:<//?php echo h($estadoAlertas->$id); ?>" aria-controls="registrado" data-toggle="tab" role="tab"><//?php echo h($estadoAlertas); ?></a></li>

        <//?php endforeach;?>
        -->
        <li role="presentation" class="active"> <?=$this->Html->link(("TODOS"), ['controller' => 'eventos', 'action' => '/index' ])?></li>

        <?php foreach ($estadosTab as $estadoAlertasTabs): ?>
            <li role="presentation" class="active"> <?=$this->Html->link(($estadoAlertasTabs["Estadoalerta"]["descripcion"]), ['controller' => 'eventos', 'action' => '/index/estadoalerta:' . ($estadoAlertasTabs["Estadoalerta"]["id"])])?></li>

            <?php endforeach;?>


       </ul>
    </div>

    <br>

        <div class="table-responsive">
            <div class="container">
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                                <th><?php echo h(''); ?></th>
                                <th><?php echo h('Tipo Evento'); ?></th>
                                <th><?php echo h('Responsable'); ?></th>
                                <th><?php echo h('Fecha Evento'); ?></th>
                                <th><?php echo h('Cliente'); ?></th>
                                <th><?php echo h('Estado'); ?></th>
                                <th><?php echo h('Teléfono'); ?></th>
                                <th><?php echo h('Placa'); ?></th>
                                <th><?php echo h('Descripción'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($eventosIndex as $evento): ?>
                <tr>

                <td>
                    <center>
                        <div style="border-width: 4px; border-radius: 25px; width: 35px;  background: #<?php
echo $color;
?>; "><?php echo "1"; ?>
                        </div>
                    </center>
                </td>
                        <td><?php echo h($tipoEventos[$evento['Evento']['tipoevento_id']]); ?>&nbsp;</td>
                        <td><?php echo h($usuarios[$evento['Evento']['usuario_id']]); ?>&nbsp;</td>
                        <td><?php echo h($evento['Evento']['fecha']); ?>&nbsp;</td>
                        <td><?php echo h($evento['Evento']['cliente']); ?>&nbsp;</td>
                        <td><?php echo h($estados[$evento['Evento']['estadoalerta_id']]); ?>&nbsp;</td>
                        <td><?php echo h($evento['Evento']['telefono']); ?>&nbsp;</td>
                        <td><?php echo h($evento['Evento']['placa']); ?>&nbsp;</td>
                        <td><?php echo h($evento['Evento']['descripcion']); ?>&nbsp;</td>
                        <td class="actions">
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Evento', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $evento['Evento']['id']))); ?>
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
	<?php
		echo $this->Paginator->prev('< ' . __('Anterior '), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ' || '));
		echo $this->Paginator->next(__(' Siguiente') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
