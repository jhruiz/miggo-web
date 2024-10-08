<?php $this->layout = 'inicio';?>
<div class="clientes index">

    <?php echo $this->Form->create('Clientes', array('action' => 'search', 'method' => 'post')); ?>
    <legend>
        <h2><b><?php echo __('Buscar Clientes'); ?></b></h2>
    </legend>
    <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '14', 'id' => 'menuvert')) ?>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group ">
                <?php echo $this->Form->input('nit', array('label' => 'Nit', 'name' => 'nit', 'placeholder' => 'Nit del Cliente', 'type' => 'text','class' => 'form-control' , 'value' => $nit)); ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group ">
                <?php echo $this->Form->input('nombre', array('label' => 'Nombre', 'name' => 'nombre', 'placeholder' => 'Nombre del Cliente', 'type' => 'text','class' => 'form-control' , 'value' => $nombre)); ?>
            </div>
        </div>

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

    </form><br><br>


    <legend>
        <h2><b><?php echo __('Clientes'); ?></b></h2>
    </legend>

    <div class="row justify-content-md-center">
        <div class="col col-lg-2">
            <button type="button" class="btn btn-primary">
                <?php echo $this->Html->link(__('Nuevo Cliente'), array('action' => 'add'), ["style" => "color:white;"]); ?>
            </button>
        </div>
        <div class="col-md-auto">
            <!-- Inicio zona descargue reporte excel-->
            <?php echo $this->Form->create('Reporte', array('controller' => 'reportes', 'action' => 'descargarReporteClientes')); ?>
            <fieldset>
                <?php echo $this->Form->input('nit', array('type' => 'hidden', 'name' => 'nit', 'value' => $nit)) ?>
                <?php echo $this->Form->input('nombre', array('type' => 'hidden', 'name' => 'nombre', 'value' => $nombre)) ?>
                <?php echo $this->Form->submit('Descargar', array('class' => 'btn btn-primary')); ?>
            </fieldset>
            </form>
            <!-- Fin zona descargue reporte excel -->
        </div>
    </div>


    <div class="table-responsive">
        <div class="container">
            <table cellpadding="0" cellspacing="0"
                class="table table-striped table-bordered table-hover table-condensed">
                <tr class="info">
                    <th><?php echo $this->Paginator->sort('nit'); ?></th>
                    <th><?php echo $this->Paginator->sort('nombre'); ?></th>
                    <th><?php echo $this->Paginator->sort('direccion', 'Dirección'); ?></th>
                    <th><?php echo $this->Paginator->sort('telefono', 'Teléfono'); ?></th>
                    <th><?php echo $this->Paginator->sort('ciudade_id'); ?></th>
                    <th><?php echo $this->Paginator->sort('celular'); ?></th>
                    <th><?php echo $this->Paginator->sort('diascredito', 'Días de Crédito'); ?></th>
                    <th><?php echo $this->Paginator->sort('limitecredito', 'Límite de Crédito'); ?></th>
                    <th><?php echo $this->Paginator->sort('estado_id'); ?></th>
                    <th><?php echo $this->Paginator->sort('clasificacioncliente_id', 'Clasificación'); ?></th>
                    <th><?php echo $this->Paginator->sort('clasificacioncliente_id', 'Clasificación'); ?></th>
                    <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?php echo h($cliente['Cliente']['nit']); ?>&nbsp;</td>
                    <td><?php echo h($cliente['Cliente']['nombre']); ?>&nbsp;</td>
                    <td><?php echo h($cliente['Cliente']['direccion']); ?>&nbsp;</td>
                    <td><?php echo h($cliente['Cliente']['telefono']); ?>&nbsp;</td>
                    <td><?php echo h($cliente['Ciudade']['descripcion']); ?>&nbsp;</td>
                    <td><?php echo h($cliente['Cliente']['celular']); ?>&nbsp;</td>
                    <td><?php echo h($cliente['Cliente']['diascredito']); ?>&nbsp;</td>
                    <td><?php echo h($cliente['Cliente']['limitecredito']); ?>&nbsp;</td>
                    <td><?php echo h($cliente['Estado']['descripcion']); ?>&nbsp;</td>
                    <td>
                        <?php echo h(!empty($cliente['Cliente']['clasificacioncliente_id'])
                        ? $clasificacion[$cliente['Cliente']['clasificacioncliente_id']]
                        : ''); ?>&nbsp;
                    </td>
                    <td><?php echo h($tipoIdent[$cliente['Cliente']['tipoidentificacione_id']]); ?>&nbsp;</td>

                    <td class="actions">
                        <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Cliente', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'view', $cliente['Cliente']['id']))); ?>
                        <?php echo $this->Html->image('png/list-12.png', array('title' => 'Editar Cliente', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $cliente['Cliente']['id']))); ?>
                    </td>
                </tr>
                <?php endforeach;?>
            </table>
        </div>
    </div>
    <p>
        <?php
echo $this->Paginator->counter(array(
    'format' => __('Página {:page} de {:pages}, mostrando {:current} registro de {:count} en total, iniciando en registro {:start}, finalizando en {:end}'),
));
?> </p>
    <div class="paging">
        <?php
echo $this->Paginator->prev('< ' . __('Anterior '), array(), null, array('class' => 'prev disabled'));
echo $this->Paginator->numbers(array('separator' => ' || '));
echo $this->Paginator->next(__(' Siguiente') . ' >', array(), null, array('class' => 'next disabled'));
?>
    </div>
</div>