<?php
$this->layout = 'inicio';
?>
<style type="text/css">
label {
            float: left;
            width: 150px;
            display: block;
            clear: left;
            text-align: left;
            cursor: hand;
        } 
</style>
<div class="usuarios form">
    <?php echo $this->Form->create('Usuario', array('method' => 'post', 'action' => 'usercambiocontrasenia')); ?>
    <fieldset>
        <legend><h2><b><?php echo __('Cambiar Contraseña'); ?></b></h2></legend>
        <?php       
        if (isset($mensaje)) {
        ?>
	        <div class="alert alert-warning">
	            <button type="button" class="close" data-dismiss="alert">&times;</button>
	            <strong><?php echo $mensaje; ?></strong>
	        </div>
	<?php         
        }
        ?>
        <table>
            <tr>
                <td>                                                           
	            <?php
	                echo $this->Form->input('passwordAnt', array('label' => '<b>Contraseña Anterior</b>', 'class' => 'form-control', 'type' => 'password', 'id' => 'UsuarioPasswordA', 'autocomplete' => 'off'));
	                echo $this->Form->input('Usuario.id',array('id' => 'selusuario','type'=>'hidden','value'=>$usuario_id)); 
	            ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php
                        echo $this->Form->input('password', array('label' => '<b>Contraseña Nueva</b>', 'class' => 'form-control', 'type' => 'password', 'id' => 'UsuarioPassword', 'autocomplete' => 'off')); 
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php
                        echo $this->Form->input('contraseniaConf', array('label' => '<b>Confirmar Contraseña</b>', 'class' => 'form-control', 'type' => 'password', 'id' => 'txtConfPass', 'autocomplete' => 'off'));
                    ?>
                </td>
            </tr>
        </table>
    </fieldset><br><br>
    <?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>

</form>
</div>