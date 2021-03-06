<?php $this->layout = 'inicio';?>
<div class="usuarios view">
<legend><h2><b><?php echo __('Usuario'); ?></b></h2></legend>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '25', 'id' => 'menuvert')) ?>
            <section class="main row">
                <div class="col-md-4">
                    <?php if ($usuario['Usuario']['imagen'] == "") {?>
                        <?php echo $this->Html->image('png/user-6.png', array('alt' => 'CakePHP', 'width' => '400', 'height' => '500')); ?>
                    <?php } else {?>
                        <img src="<?php echo $urlImagen . $usuario['Usuario']['imagen']; ?>" class="img-responsive img-thumbnail">
                    <?php }?>
                </div>
                <div class="col-md-4">
                    <dl>
                            <dt class="text-info"><?php echo __('Nombre'); ?></dt>
                            <dd>
                                    <?php echo h($usuario['Usuario']['nombre']); ?>
                                    &nbsp;
                            </dd><br>
                            <dt class="text-info"><?php echo __('CC/NIT'); ?></dt>
                            <dd>
                                    <?php echo h($usuario['Usuario']['identificacion']); ?>
                                    &nbsp;
                            </dd><br>
                            <dt class="text-info"><?php echo __('Username'); ?></dt>
                            <dd>
                                    <?php echo h($usuario['Usuario']['username']); ?>
                                    &nbsp;
                            </dd><br>
                            <dt class="text-info"><?php echo __('Perfil'); ?></dt>
                            <dd>
                                    <?php echo $this->Html->link($usuario['Perfile']['descripcion'], array('controller' => 'perfiles', 'action' => 'view', $usuario['Perfile']['id'])); ?>
                                    &nbsp;
                            </dd><br>
                            <dt class="text-info"><?php echo __('Estado'); ?></dt>
                            <dd>
                                    <?php echo $usuario['Estado']['descripcion']; ?>
                                    &nbsp;
                            </dd><br>
                    </dl>
                </div>
            </section>
            <br>
</div>