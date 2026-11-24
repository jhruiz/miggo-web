<?php $this->layout = 'inicio';?>
<div class="clientes view">
<legend><h2><b><?php echo __('Cliente'); ?></b></h2></legend>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '14', 'id' => 'menuvert')) ?>
<section class="main row">
    <div class="col-md-4">
        <dl>
        <dt class="text-info"><?php echo __('Nit'); ?></dt>
        <dd>
                <?php echo h($cliente['Cliente']['nit']); ?>
                &nbsp;
        </dd><br>
        <dt class="text-info"><?php echo __('Teléfono'); ?></dt>
        <dd>
                <?php echo h($cliente['Cliente']['telefono']); ?>
                &nbsp;
        </dd><br>
        <dt class="text-info"><?php echo __('Email'); ?></dt>
        <dd>
                <?php echo h($cliente['Cliente']['email']); ?>
                &nbsp;
        </dd><br>
        <dt class="text-info"><?php echo __('Limite de Crédito'); ?></dt>
        <dd>
                <?php echo h($cliente['Cliente']['limitecredito']); ?>
                &nbsp;
        </dd><br>
        <dt class="text-info"><?php echo __('Usuario'); ?></dt>
        <dd>
                <?php echo $this->Html->link($cliente['Usuario']['nombre'], array('controller' => 'usuarios', 'action' => 'view', $cliente['Usuario']['id'])); ?>
                &nbsp;
        </dd><br>
    </div>

    <div class="col-md-4">
        <dt class="text-info"><?php echo __('Nombre'); ?></dt>
        <dd>
                <?php echo h($cliente['Cliente']['nombre']); ?>
                &nbsp;
        </dd><br>
        <dt class="text-info"><?php echo __('Ciudad'); ?></dt>
        <dd>
                <?php echo h($cliente['Ciudade']['descripcion']); ?>
                &nbsp;
        </dd><br>
        <dt class="text-info"><?php echo __('Celular'); ?></dt>
        <dd>
                <?php echo h($cliente['Cliente']['celular']); ?>
                &nbsp;
        </dd><br>
        <dt class="text-info"><?php echo __('Cumpleaños'); ?></dt>
        <dd>
                <?php echo h($cliente['Cliente']['cumpleanios']); ?>
                &nbsp;
        </dd><br>
        <dt class="text-info"><?php echo __('Estado'); ?></dt>
        <dd>
                <?php echo h($cliente['Estado']['descripcion']); ?>
                &nbsp;
        </dd><br>
    </div>

    <div class="col-md-4">
        <dt class="text-info"><?php echo __('Dirección'); ?></dt>
        <dd>
                <?php echo h($cliente['Cliente']['direccion']); ?>
                &nbsp;
        </dd><br>
        <dt class="text-info"><?php echo __('Pagina Web'); ?></dt>
        <dd>
                <?php echo h($cliente['Cliente']['paginaweb']); ?>
                &nbsp;
        </dd><br>
        <dt class="text-info"><?php echo __('Días de Crédito'); ?></dt>
        <dd>
                <?php echo h($cliente['Cliente']['diascredito']); ?>
                &nbsp;
        </dd><br>
        <dt class="text-info"><?php echo __('Observaciones'); ?></dt>
        <dd>
                <?php echo h($cliente['Cliente']['observaciones']); ?>
                &nbsp;
        </dd><br>
        <dt class="text-info"><?php echo __('Fecha'); ?></dt>
        <dd>
                <?php echo h($cliente['Cliente']['created']); ?>
                &nbsp;
        </dd><br>
    </div>
    </dl>
</section>

<section class="row">
    <div class="col-md-12">
        <h4><b><?php echo __('Datos tributarios'); ?></b></h4>
        <?php
        $tiposPersona = array('NATURAL' => 'Persona natural', 'JURIDICA' => 'Persona jurídica', 'ENTIDAD_PUBLICA' => 'Entidad pública');
        $practica = array();
        if (!empty($cliente['Cliente']['practica_retefuente'])) { $practica[] = 'ReteFuente'; }
        if (!empty($cliente['Cliente']['practica_reteiva'])) { $practica[] = 'ReteIVA'; }
        if (!empty($cliente['Cliente']['practica_reteica'])) { $practica[] = 'ReteICA'; }
        ?>
        <p>
            <b>DV:</b> <?php echo isset($cliente['Cliente']['dv']) ? h($cliente['Cliente']['dv']) : '-'; ?>
            &nbsp;|&nbsp; <b>Tipo de persona:</b> <?php echo !empty($cliente['Cliente']['tipo_persona']) ? h($tiposPersona[$cliente['Cliente']['tipo_persona']]) : 'Sin definir'; ?>
            &nbsp;|&nbsp; <b>Practica:</b> <?php echo $practica ? h(implode(', ', $practica)) : 'Ninguna retención'; ?>
            &nbsp;|&nbsp; <b>Revisado:</b> <?php echo !empty($cliente['Cliente']['datos_tributarios_revisados']) ? 'Sí' : 'No'; ?>
        </p>
    </div>
</section>




</div>