<?php $this->layout = 'inicio'; ?>
<div class="impuestos form">

<?php echo $this->Form->create('Impuesto'); ?>

<fieldset>
    <legend><h2><b><?php echo __('Agregar Impuesto'); ?></b></h2></legend>

    <?php
    echo $this->Form->input('menuvert', array(
        'type' => 'hidden',
        'value' => '20'
    ));
    ?>

    <!-- Impuesto -->
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="ImpuestoTaxId">Impuesto</label>
                <?php
                echo $this->Form->input('tax_id', array(
                    'type' => 'select',
                    'options' => $taxes,
                    'empty' => '-- Seleccione un impuesto --',
                    'label' => false,
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>
    </div>

    <!-- Tipo de valor -->
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Tipo de valor</label>
                <div>
                    <?php
                    echo $this->Form->radio('valoprc', array(
                        '1' => ' Porcentaje',
                        '0'      => ' Valor'
                    ), array(
                        'legend' => false,
                        'separator' => '<br>'
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Valor -->
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label for="ImpuestoValor">Valor</label>
                <?php
                echo $this->Form->input('valor', array(
                    'label' => false,
                    'class' => 'form-control',
                    'placeholder' => 'Valor'
                ));
                ?>
            </div>
        </div>
    </div>

    <?php
    echo $this->Form->input('empresa_id', array(
        'type' => 'hidden',
        'value' => $empresaId
    ));
    ?>

</fieldset>

<?php echo $this->Form->submit('Guardar', array('class' => 'btn btn-primary')); ?>

</div>
