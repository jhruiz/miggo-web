<?php $this->layout = 'inicio'; ?>
<?php echo ($this->Html->script('reciboscajas/reciboscajas.js')); ?>

<div class="reciboscajas form container-fluid" style="padding: 20px;">

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h3 class="card-title mb-0">
                <b><i class="fa fa-file-text-o"></i> Nuevo Recibo de Caja / Anticipo</b>
            </h3>
        </div>

        <div class="card-body bg-light-gray">
            <?php echo $this->Form->create('Reciboscaja', array('type' => 'post', 'class' => 'form-horizontal', 'id' => 'formRecibo')); ?>

            <!-- Campos ocultos -->
            <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId)); ?>
            <?php echo $this->Form->input('usuario_id', array('type' => 'hidden', 'value' => $usuarioId)); ?>

            <!-- ── SECCIÓN: DATOS DEL CLIENTE ── -->
            <h5 style="color:#2A3F54; border-bottom:2px solid #e0e0e0; padding-bottom:8px; margin-bottom:20px;">
                <i class="fa fa-user"></i> Datos del Cliente
            </h5>

            <div class="row">
                <!-- Búsqueda de cliente -->
                <div class="col-md-5">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Buscar Cliente <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text"
                                   id="inputBuscarCliente"
                                   class="form-control"
                                   placeholder="Escriba nombre o NIT del cliente..."
                                   autocomplete="off">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="btnBuscarCliente">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                        <!-- Resultados de búsqueda -->
                        <div id="resultadosCliente" class="list-group" style="position:absolute; z-index:9999; width:calc(100% - 30px); display:none;"></div>
                    </div>
                </div>

                <!-- Cliente seleccionado -->
                <div class="col-md-7">
                    <div id="panelClienteSeleccionado" style="display:none;">
                        <div class="alert alert-info" style="padding:10px 15px; margin-bottom:0;">
                            <i class="fa fa-check-circle"></i>
                            <b id="lblNombreCliente"></b>
                            <span class="text-muted" style="margin-left:10px;">NIT: <span id="lblNitCliente"></span></span>
                            <a href="javascript:void(0);" id="lnkCambiarCliente" style="float:right; color:#c0392b;" title="Cambiar cliente">
                                <i class="fa fa-times"></i> Cambiar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campo oculto con el ID del cliente seleccionado -->
            <?php echo $this->Form->input('cliente_id', array('type' => 'hidden', 'id' => 'clienteIdSeleccionado')); ?>

            <hr>

            <!-- ── SECCIÓN: DATOS DEL RECIBO ── -->
            <h5 style="color:#2A3F54; border-bottom:2px solid #e0e0e0; padding-bottom:8px; margin-bottom:20px;">
                <i class="fa fa-money"></i> Datos del Pago
            </h5>

            <div class="row">
                <!-- Valor -->
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Valor Recibido <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <?php echo $this->Form->input('valor', array(
                                'type'       => 'text',
                                'label'       => false,
                                'class'       => 'form-control numericPrice',
                                'placeholder' => '0',
                                'id'          => 'inputValor',
                            )); ?>
                        </div>
                    </div>
                </div>

                <!-- Tipo de pago -->
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Tipo de Pago <span class="text-danger">*</span></label>
                        <?php echo $this->Form->input('tipopago_id', array(
                            'label'   => false,
                            'type'    => 'select',
                            'class'   => 'form-control select2',
                            'empty'   => 'Seleccione...',
                            'options' => $tipoPagos,
                        )); ?>
                    </div>
                </div>

                <!-- Fecha (solo informativo, created se asigna en el servidor) -->
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Fecha</label>
                        <input type="text" class="form-control" value="<?php echo date('d/m/Y H:i'); ?>" readonly style="background:#f5f5f5;">
                    </div>
                </div>
            </div>

            <!-- Concepto -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Concepto / Observación</label>
                        <?php echo $this->Form->input('concepto', array(
                            'label'       => false,
                            'type'        => 'textarea',
                            'class'       => 'form-control',
                            'rows'        => 2,
                            'placeholder' => 'Motivo del pago, referencia, número de transferencia, etc.',
                        )); ?>
                    </div>
                </div>
            </div>

            <hr>

            <!-- ── BOTONES ── -->
            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary btn-lg" id="btnGuardarRecibo">
                        <i class="fa fa-save"></i> Guardar Recibo de Caja
                    </button>
                    &nbsp;
                    <?php echo $this->Html->link(
                        '<i class="fa fa-arrow-left"></i> Cancelar',
                        array('action' => 'index'),
                        array('class' => 'btn btn-default btn-lg', 'escape' => false)
                    ); ?>
                </div>
            </div>

            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<input type="hidden" id="baseUrl" value="<?php echo $this->request->base; ?>">