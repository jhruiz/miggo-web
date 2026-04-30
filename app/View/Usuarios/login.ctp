<?php $this->layout = 'inicio'; ?>

<style type="text/css">
    .miggo-login-container {
        display: flex;
        width: 100vw;
        height: 100vh;
        margin: 0;
        background-color: #000;
    }

    .login-panel {
        width: 400px;
        background: #000;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 50px;
        z-index: 10;
    }

    .client-photo {
        flex: 1;
        background: url("/img/login.jpg") no-repeat center center;
        background-size: cover;
    }

    .login-logo-central {
        text-align: center;
        margin-bottom: 40px;
    }

    .login-logo-central img {
        max-width: 250px;
        height: auto;
    }

    .form-group-miggo { margin-bottom: 20px; }
    .form-group-miggo label { 
        color: #fff; 
        text-transform: uppercase; 
        font-size: 12px; 
        letter-spacing: 1px; 
        margin-bottom: 8px; 
        display: block;
    }
    
    .input-miggo {
        background: rgba(255,255,255,0.1) !important;
        border: 1px solid rgba(255,255,255,0.2) !important;
        border-radius: 4px !important;
        color: #fff !important;
        height: 45px !important;
        width: 100% !important;
        padding-left: 15px !important;
    }

    .btn-miggo {
        width: 100%;
        height: 45px;
        background: #3498db;
        border: none;
        color: #fff;
        font-weight: bold;
        text-transform: uppercase;
        border-radius: 4px;
        margin-top: 10px;
        cursor: pointer;
    }
</style>

<div class="miggo-login-container">
    <div class="login-panel animated fadeInLeft">
        <div class="login-logo-central">
            <img src="/img/png/miggologin.png" alt="Logo Miggo">
        </div>

        <?php echo $this->Session->flash('auth'); ?>

        <?php echo $this->Form->create('Usuario', array('action' => 'login')); ?>
            <div class="form-group-miggo">
                <label>Usuario</label>
                <?php echo $this->Form->input('username', array('label' => false, 'class' => 'input-miggo', 'placeholder' => 'Usuario', 'id' => 'UsuarioUsername')); ?>
            </div>

            <div class="form-group-miggo">
                <label>Clave</label>
                <?php echo $this->Form->input('password', array('label' => false, 'class' => 'input-miggo', 'placeholder' => 'Password')); ?>
            </div>

            <?php echo $this->Form->submit('Ingresar', array('class' => 'btn-miggo')); ?>
        <?php echo $this->Form->end(); ?>
    </div>

    <div class="client-photo animated fadeIn"></div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#UsuarioUsername").focus();
    });
</script>