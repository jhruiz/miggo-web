<?php $this->layout = 'inicio';?>
<div class="cotizaciones index">
    <legend><h2><b><?php echo __('Cotizaciones'); ?></b></h2></legend>

    <div class="x_panel">

        <div class="x_title">
            <h2><b><?php echo __('Buscar Cotizaciones'); ?></b></h2>
        </div>
        <?php echo $this->Form->create('Cotizaciones', array('action' => 'search', 'method' => 'post', 'class' => 'form-inline')); ?>

            <div class="row" style="margin-bottom: 20px">

                <div class="col-md-12">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cliente">Cliente</label><br>
                            <input name="cliente" id="cliente" autocomplete="off" class="form-control" placeholder="Nombre del cliente" type="text">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="usuario">Usuario</label><br>
                            <?php echo $this->Form->input('usuario', array('label' => '', 'name' => 'usuario', 'empty' => 'Seleccione uno', 'type' => 'select', 'options' => $usuario, 'class' => 'form-control')); ?>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fechacotizacion">Fecha factura inicio</label><br>
                            <input name="fechacotizacion" id="fechacotizacion" autocomplete="off" class="date form-control" placeholder="Fecha inicio" type="text">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fechacotizacion_fin">Fecha factura fin</label><br>
                            <input name="fechacotizacion_fin" id="fechacotizacion_fin" autocomplete="off" class="date form-control" placeholder="Fecha de fin" type="text">
                        </div>
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

        </form>
    </div><!-- class="x_panel" -->

     <!--Enlaces de acción -->
	 <div class="actions">
        <button type="button" class="btn btn-primary">
            <?php echo $this->Html->link(__('Nueva Cotizacion'), array('action' => 'add'), ["style" => "color:white;"]); ?>
        </button>
	</div>
	<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '48', 'id' => 'menuvert')) ?>
        <div class="table-responsive">
            <div class="container">
                <table cellpadding="0" cellspacing="0" class="table">
                <tr>
                                <th><?php echo __('Usuario'); ?></th>
                                <th><?php echo __('Fecha Cotizacion'); ?></th>
                                <th><?php echo __('Cliente'); ?></th>
                                <th><?php echo __('Dirección Cliente'); ?></th>
                                <th><?php echo __('Celular Cliente'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($arrCot as $cot): ?>
                <tr>
                    <td><?php echo h($cot['U']['nombre']); ?>&nbsp;</td>
                    <td><?php echo h($cot['Cotizacione']['created']); ?>&nbsp;</td>
                    <td><?php echo h($cot['CL']['nombre']); ?>&nbsp;</td>
                    <td><?php echo h($cot['CL']['direccion']); ?>&nbsp;</td>
                    <td><?php echo h($cot['CL']['celular']); ?>&nbsp;</td>
                    <td class="actions">
                        <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Cotización', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $cot['Cotizacione']['id']))); ?>
                        <?php
                        echo $this->Form->postLink(
                            $this->Html->image('png/list-2.png', array('title' => 'Eliminar Cotización', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                            array('action' => 'delete', $cot['Cotizacione']['id']), //url
                            array('escape' => false), //el escape
                            __('Está seguro que desea eliminar la cotización?') //la confirmacion
                        );
                        ?>
                    </td>
                </tr>
                <?php endforeach;?>
                </table>
            </div>
        </div>
</div>