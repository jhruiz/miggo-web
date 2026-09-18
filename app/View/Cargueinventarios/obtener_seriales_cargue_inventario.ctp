<?php 
$this->layout = false;
echo ($this->Html->script('prefacturas/seleccionSerial.js'));
?>

<legend><center><h4><?php echo __('Selección de Serial y Datos del Vehículo'); ?></h4></center></legend>           

<section class="main row">
    <!-- CAMPOS COLOR Y MODELO -->
    <div class="col-md-6">
        <div class="form-group form-inline"> 
            <label><?php echo __('Color de Moto'); ?></label><br>
            <div class="input-group" style="width: 100%;">
                <span class="input-group-addon"><i class="glyphicon glyphicon-tint"></i></span>                    
                <?php echo $this->Form->input('moto_color', array(
                    'label'       => false, 
                    'id'          => 'motoColor',
                    'class'       => 'form-control', 
                    'placeholder' => __('Ej: Negro Mate'),
                    'autocomplete'=> 'off',
                    'value' => $color
                )); ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group form-inline"> 
            <label><?php echo __('Modelo (Año)'); ?></label><br>
            <div class="input-group" style="width: 100%;">
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>                    
                <?php echo $this->Form->input('moto_modelo', array(
                    'label'       => false, 
                    'id'          => 'motoModelo',
                    'class'       => 'form-control', 
                    'placeholder' => __('Ej: 2026'),
                    'autocomplete'=> 'off',
                    'value' => $modelo
                )); ?>
            </div>
        </div>
    </div>

    <!-- LISTADO DE SERIALES CON RADIO BUTTON -->
    <div class="col-md-12" style="margin-top: 15px;">
        <legend><h4><center><b><?php echo __('Seleccionar Chasis y Motor'); ?></b></center></h4></legend>
        <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;"><?php echo __('Sel'); ?></th>
                        <th><?php echo __('Estado'); ?></th>
                        <th><?php echo __('Número de Chasis'); ?></th>
                        <th><?php echo __('Número de Motor'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($seriales)): ?>
                        <?php foreach($seriales as $idx => $ser): ?>
                            <?php 
                                // Validar si el serial está asignado a otra prefactura/detalle
                                $estaInhabilitado = !empty($ser['SM']['prefacturasdetalle_id']) && 
                                                    (isset($prefacturasdetalleId) && $ser['SM']['prefacturasdetalle_id'] != $prefacturasdetalleId);
                            ?>
                            <tr class="<?php echo $estaInhabilitado ? 'text-muted bg-light' : ''; ?>">
                                <td class="text-center">
                                    <input 
                                        type="radio" 
                                        name="rd_serial_id" 
                                        class="rd_serial_selec" 
                                        value="<?php echo $ser['SM']['id']; ?>"
                                        data-chasis="<?php echo h($ser['SM']['chasis']); ?>"
                                        data-motor="<?php echo h($ser['SM']['motor']); ?>"
                                        <?php echo (isset($serialSeleccionado) && $ser['SM']['id'] == $serialSeleccionado) ? 'checked="checked"' : ''; ?>
                                        <?php echo $estaInhabilitado ? 'disabled="disabled"' : ''; ?>>
                                </td>
                                <td class="text-center">
                                    <?php if ($estaInhabilitado) { ?>
                                        <small class="label label-default"><?php echo __('Ocupado'); ?></small>
                                    <?php } else { ?>
                                        <small class="label label-default"><?php echo __('Disponible'); ?></small>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php echo h($ser['SM']['chasis']); ?>
                                </td>
                                <td><?php echo h($ser['SM']['motor']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                <?php echo __('No hay seriales disponibles.'); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Sección para campos ocultos -->
<input type="hidden" id="prefacturasdetalleId" value="<?php echo $prefacturasdetalleId;?>">

<!-- BOTÓN DE ACCIÓN -->
<div class="container-fluid" style="margin-top: 15px;">
    <button id="btn_guardarEst" class="btn btn-primary center-block" onclick="agregarSerialPreFactura()">
        <?php echo __('Agregar'); ?>
    </button>
</div>