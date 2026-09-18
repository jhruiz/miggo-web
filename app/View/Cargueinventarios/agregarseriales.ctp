
<?php
    echo $this->Html->script('cargarinventario/cargarinventario.js');
    $this->layout = false;
?>

<style type="text/css">

    .serial-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
    }

    .serial-header {
        text-align: center;
        margin-bottom: 25px;
    }

    .serial-header h3 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
        color: #333;
    }

    .serial-header p {
        margin-top: 8px;
        color: #777;
        font-size: 14px;
    }

    .serial-card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 6px;
        margin-bottom: 18px;
        padding: 0;
        box-shadow: 0 2px 6px rgba(0,0,0,.08);
    }

    .serial-card-header {
        background: #f5f6f8;
        border-bottom: 1px solid #ddd;
        padding: 12px 18px;
        font-weight: 600;
        color: #333;
        font-size: 15px;
    }

    .serial-card-body {
        padding: 20px;
    }

    .serial-field {
        margin-bottom: 0;
    }

    .serial-field label {
        display: block;
        font-weight: 600;
        margin-bottom: 7px;
        color: #555;
    }

    .serial-field .form-control {
        height: 40px;
        border-radius: 4px;
    }

    .serial-field .input-group {
        width: 100%;
    }

    .serial-number {
        display: inline-block;
        width: 28px;
        height: 28px;
        line-height: 28px;
        text-align: center;
        border-radius: 50%;
        background: #337ab7;
        color: #fff;
        margin-right: 8px;
        font-size: 13px;
    }

    .serial-actions {
        text-align: center;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .btn-agregar-seriales {
        min-width: 180px;
        padding: 10px 25px;
        font-size: 15px;
        font-weight: 600;
    }

    @media (max-width: 767px) {

        .serial-container {
            padding: 10px;
        }

        .serial-card-body {
            padding: 15px;
        }

        .serial-field {
            margin-bottom: 15px;
        }

        .serial-field:last-child {
            margin-bottom: 0;
        }
    }
</style>

<?php
    echo $this->Form->input(
        "productoSerialId",
        array(
            'id'    => "productoSerialId",
            'type'  => 'hidden',
            'value' => $productoId
        )
    );
?>

<div class="serial-container">

    <!-- ENCABEZADO -->
    <div class="serial-header">
        <h3>
            <?php echo __('Agregar Seriales'); ?>
        </h3>
        <p>
            <?php echo __('Ingrese el número de chasis y motor para cada unidad.'); ?>
        </p>
    </div>

    <!-- SERIALes -->
    <?php for($i = 1; $i <= $cantidad; $i++) { ?>
        <div class="serial-card">
            <div class="serial-card-header">
                <span class="serial-number">
                    <?php echo $i; ?>
                </span>
                <?php echo __('Serial'); ?>
                #<?php echo $i; ?>
            </div>

            <div class="serial-card-body">
                <div class="row">
                    <!-- CHASIS -->
                    <div class="col-md-6">
                        <div class="form-group serial-field">
                            <label>
                                <span class="glyphicon glyphicon-barcode"></span>
                                <?php echo __('Chasis'); ?>
                            </label>
                            <?php
                                echo $this->Form->input(
                                    'chasis_' . $i,
                                    array(
                                        'label'       => false,
                                        'class'       => 'form-control',
                                        'placeholder' => __('Número del chasis'),
                                        'autocomplete'=> 'off',
                                        'id' => 'serial_chasis_' . $i
                                    )
                                );
                            ?>
                        </div>
                    </div>

                    <!-- MOTOR -->
                    <div class="col-md-6">
                        <div class="form-group serial-field">
                            <label>
                                <span class="glyphicon glyphicon-cog"></span>
                                <?php echo __('Motor'); ?>
                            </label>
                            <?php
                                echo $this->Form->input(
                                    'motor_' . $i,
                                    array(
                                        'label'       => false,
                                        'class'       => 'form-control',
                                        'placeholder' => __('Número del motor'),
                                        'autocomplete'=> 'off',
                                        'id' => 'serial_motor_' . $i
                                    )
                                );
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <!-- BOTÓN -->
    <div class="serial-actions">
        <button
            type="button"
            id="btn_guardarEst"
            class="btn btn-primary btn-agregar-seriales"
            onclick="agregarSerialesProducto()">

            <?php echo __('Agregar Seriales'); ?>
        </button>
    </div>
</div>
