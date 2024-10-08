<?php echo ($this->Html->script('bandeja/gestionBandejas')); ?>
<?php echo ($this->Html->script('clientes/clientes.js')); ?>
<?php $this->layout = 'inicio';?>
<div class="clientes form">
<?php echo $this->Form->create('Cliente', array('type' => 'post')); ?>
    <fieldset>
    <legend><h2><b><?php echo __('Agregar Cliente'); ?></b></h2></legend>
    <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '14', 'id' => 'menuvert')) ?>
    <section class="main row">

    <div class="row">
    <div class="form-group col-md-3">
        <label for="ClienteNit">Nit</label>
        <?php echo $this->Form->input('nit', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Nit del Cliente')); ?>
    </div>
    <div class="form-group col-md-3">
        <label for="ClienteNombre">Nombre</label>
        <?php echo $this->Form->input('nombre', array('label' => '', 'class' => 'form-control', 'type' => 'text', 'placeholder' => 'Nombre del Cliente')); ?>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-3">
        <label for="ClienteTelefono">Teléfono</label>
        <?php echo $this->Form->input('telefono', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Teléfono del Cliente', 'autocomplete' => 'off')); ?>
    </div>
    <div class="form-group col-md-3">
        <label for="ClienteDireccion">Dirección</label>
        <?php echo $this->Form->input('direccion', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Dirección del Cliente', 'autocomplete' => 'off')); ?>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-3">
        <label for="ClienteEmail">Email</label>
        <?php echo $this->Form->input('email', array('label' => '', 'class' => 'form-control', 'placeholder' => 'E-mail del Cliente', 'autocomplete' => 'off')); ?>
    </div>
    <div class="form-group col-md-3">

    <label for="cumpleanios">Cumpleaños</label><br><br>
        <input name="data[Cliente][cumpleanios]" class="date form-control" placeholder="Cumpleaños Cliente" id="cumpleanios">

    </div>
</div>

<div class="row">
    <div class="form-group col-md-3">
        <label for="ClienteCelular">Celular</label>
        <?php echo $this->Form->input('celular', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Celular del Cliente')); ?>
    </div>
    <div class="form-group col-md-3">

    <label for="ClientePaginaweb">Página Web</label>
        <?php echo $this->Form->input('paginaweb', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Página Web del Cliente')); ?>

    </div>
</div>

<div class="row">
    <div class="form-group col-md-3">
    <label for="ClienteLimitecredito">Límite Crédito</label>
        <?php echo $this->Form->input('limitecredito', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Límite de Crédito Sugerido')); ?>

    </div>
    <div class="form-group col-md-3">
        <label for="ClienteDiascredito">Días de Crédito</label>
        <?php echo $this->Form->input('diascredito', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Días de Crédito Sugeridos')); ?>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label for="ClienteCiudadeId">Ciudad</label>
        <?php echo $this->Form->input('ciudade_id', array('label' => '', 'class' => 'form-control')); ?>
    </div>
</div>
<div class="row">

<div class="form-group col-md-3">
        <label for="ClienteDepositoId">Bodega</label>
         <?php echo $this->Form->input('deposito_id', array('label' => '', 'class' => 'form-control')); ?>
    </div>
    <div class="form-group col-md-3">
        <label for="ClienteClasificacionclienteId">Clasificación</label>
        <?php echo $this->Form->input("clasificacioncliente_id",
    array(
        'name' => "data[Cliente][clasificacioncliente_id]",
        'label' => "",
        'type' => 'select',
        'options' => $clasificacion,
        'class' => 'form-control',
            )
        );
        ?>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label for="TipoIdentificacionId">Tipo Identificación</label>
        <?php echo $this->Form->input("tipoidentificacione_id",
            array(
                'name' => "data[Cliente][tipoidentificacione_id]",
                'label' => "",
                'type' => 'select',
                'options' => $tipoIdent,
                'class' => 'form-control',
            )
        );
        ?>
    </div>
</div>

<div class="row">

<div class="form-group col-md-6">
<label for="ClienteObservaciones">Observaciones</label><br>
                <?php echo $this->Form->input('observaciones', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Observaciones sobre el Cliente')); ?>
    </div>
    </div>
</div>

            <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId)); ?>
            <?php echo $this->Form->input('usuario_id', array('type' => 'hidden', 'value' => $usuarioId)); ?>
            <?php echo $this->Form->input('estado_id', array('type' => 'hidden', 'value' => '1')); ?>

    </section>

	</fieldset>
<?php echo $this->Form->submit('Guardar', array('class' => 'btn btn-primary')); ?>
</div>