<?php $this->layout = 'inicio'; ?>
<?php echo ($this->Html->css('productos/verproducto.css')); ?>

<div class="empresas view container-fluid" id="fichaView" style="padding:20px;">
    <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '41', 'id' => 'menuvert')) ?>
    
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-12">
            <h2><b>Perfil Corporativo</b></h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="panel panel-default panel-moderno">
                <div class="panel-body text-center" style="padding:40px;">
                    <div class="mb-3">
                        <?php if (empty($empresa['Empresa']['imagen'])) : ?>
                            <?php echo $this->Html->image('png/image-4.png', array('alt' => 'Logo predeterminado', 'class' => 'img-responsive center-block', 'style' => 'max-height: 250px;')); ?>
                        <?php else : ?>
                            <img src="<?php echo $urlImagen . $empresa['Empresa']['id'] . "/" . $empresa['Empresa']['imagen']; ?>" 
                                 class="img-responsive center-block" 
                                 style="max-height: 250px; object-fit: contain;" 
                                 alt="Logo Empresa">
                        <?php endif; ?>
                    </div>
                    <div style="margin-top:20px;">
                        <span class="stock-badge label label-primary" style="font-size: 14px; padding: 8px 15px;">
                            NIT: <?php echo h($empresa['Empresa']['nit']); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="panel panel-default panel-moderno" style="padding: 25px;">
                <div class="panel-body" style="padding: 0;">
                    <div class="row" style="margin-bottom: 25px;">
                        <div class="col-md-12 text-center">
                            <h1 style="margin-top: 0; color: #2c3e50;"><b><?php echo h($empresa['Empresa']['nombre']); ?></b></h1>
                            <p class="text-muted"><i class="fa fa-university"></i> Razón Social Registrada</p>
                            <hr style="border-top: 1px solid #eee; width: 50%;">
                        </div>
                    </div>

                    <div class="info-tec row" style="margin-bottom: 25px;">
                        <div class="col-sm-6">
                            <label><i class="fa fa-map-marker text-info"></i> Dirección</label>
                            <p><?php echo h($empresa['Empresa']['direccion']); ?></p>
                        </div>
                        <div class="col-sm-6">
                            <label><i class="fa fa-globe text-info"></i> Ciudad</label>
                            <p><?php echo h($empresa['Ciudadesmiggo']['nombre']); ?></p>
                        </div>
                    </div>

                    <div class="info-tec row" style="margin-bottom: 25px;">
                        <div class="col-sm-6">
                            <label><i class="fa fa-phone text-info"></i> Teléfonos</label>
                            <p>
                                <?php echo h($empresa['Empresa']['telefono1']); ?> 
                                <?php echo !empty($empresa['Empresa']['telefono2']) ? ' | ' . h($empresa['Empresa']['telefono2']) : ''; ?>
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <label><i class="fa fa-envelope text-info"></i> Correo Electrónico</label>
                            <p><?php echo h($empresa['Empresa']['email']); ?></p>
                        </div>
                    </div>

                    <div class="info-tec row text-center" style="margin-bottom: 5px; background: #fafafa; padding: 20px; border-radius: 8px; border-left: 5px solid #3498db;">
                        <div class="col-sm-12">
                            <label class="text-muted" style="text-transform: uppercase; font-size: 11px; letter-spacing: 1px;">Representante Legal</label>
                            <h3 style="margin: 5px 0; color: #333;"><b><?php echo h($empresa['Empresa']['representantelegal']); ?></b></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top:20px; margin-bottom: 30px;">
        <div class="col-md-12 text-right">
            <?php echo $this->Html->link('<i class="fa fa-edit"></i> Editar Empresa', array('action' => 'edit', $empresa['Empresa']['id']), array('class' => 'btn btn-warning btn-lg px-5', 'escape' => false, 'style' => 'font-weight:bold;')); ?>
        </div>
    </div>
</div>