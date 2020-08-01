<?php $this->layout = 'inicio'; ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#UsuarioUsername").focus();
    });
</script>

<!--
<div class="container">
     <?php echo $this->Session->flash('auth'); ?>
            <?php echo $this->Form->create('Usuario',array('class' => 'form-horizontal', 'controller' => 'usuarios','action'=>'login')); ?>
  <div class="row">
    <div class="col-sm-5 col-xs-12 no-float stylepurple" >
        <div class=" logincontainer stylepurple">
            <div class="panel-body ">
              <div class="row">
                <div class="form-group col-sm-12 ">
                 <h1><?php echo __('Login Form'); ?></h1>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-sm-12 ">
                    <div class="input-group">
                        <span class="input-group-addon">
                          <span class="glyphicon glyphicon-user"></span>
                        </span>
                              <?php echo $this->Form->input('username',array('label' => '', 'class' => "form-control", 'placeholder' => 'Usuario'));?>
                      </div>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-sm-12 ">
                    <div class="input-group">
                        <span class="input-group-addon">
                          <span class="glyphicon glyphicon-lock"></span>
                        </span>
                         <?php echo $this->Form->input('password',array('label' => '', 'class' => "form-control", 'autocomplete' => 'off', 'placeholder' => 'Password'));?>
                      </div>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-sm-12 ">
                  <div  align="right">
                  <?php echo $this->Form->submit('Ingresar',array('class'=>'btn btn-primary'));?>  
                    </div>
                </div>
              </div>
            </div>
          </div>
       </div>
   <div class="col-sm-7 col-xs-7 no-float stylegreen"  id="logoscoomeva">
        <div class="imgcontainer stylegreen">
            <div class="panel-body">
              <div class="row">
                <div class="form-group col-sm-12 nopadding">
                 
                   <img src="/img/png/torquelogo.png" >
                </div>
              </div>
            </div>
          </div>
        
    </div>

    </div>
    </form>
    </div>
   
-->
    
    <!--<div class="container bg" >
      <div class="row">
            <?php echo $this->Session->flash('auth'); ?>
            <?php echo $this->Form->create('Usuario',array('class' => 'form-horizontal', 'controller' => 'usuarios','action'=>'login')); ?>
        <div class="col-md-12">
           <div class="col-md-2 col-md-offset-5">
            <div class="panel panel-primary">
            
                <div class="panel-heading">
                   <img src="/img/png/torqueracingp.png" >
                </div>     
                <div class="panel-body">
                    <div class="form-group">   
                        <?php echo $this->Form->input('username',array('label' => 'Usuario', 'class' => "form-control", 'autocomplete' => 'off', 'placeholder' => 'Usuario'));?>
                    </div>


                    <div class="form-group"> 
                        <?php echo $this->Form->input('password',array('label' => 'Contraseña: ', 'class' => "form-control", 'autocomplete' => 'off', 'placeholder' => 'Password'));?>
                    </div>

                    <div class="form-group">            
                            <?php echo $this->Form->input('isLogin',array('id' => 'isLogin',  'type' => 'hidden','value' => true));?>
                    </div>

                    <div class="form-group">
                        <div class="col-md-1">
                        <?php echo $this->Form->submit('Ingresar',array('class'=>'btn btn-primary'));?>   
                        </div>
                    </div>                
                </div>
            </div>
       </div>
       </div>
        </form>
        </div>
    </div>
-->
<div class="container bg">
     <?php echo $this->Session->flash('auth'); ?>
            <?php echo $this->Form->create('Usuario',array('class' => 'form-horizontal', 'controller' => 'usuarios','action'=>'login')); ?>
    <div class="row vertical-offset-100 " >
        <div class="col-md-4 col-md-offset-5">
            <div class="panel panel-default cac">
                <div class="panel-heading cac_title">
                    <img src="/img/png/torqueracingp.png" >
                </div>
                <div class="panel-body">
                    <form accept-charset="UTF-8" role="form">
                    <fieldset>
                        <div class="form-group">
                            <?php echo $this->Form->input('username',array('label' => 'Usuario', 'class' => "form-control", 'autocomplete' => 'off', 'placeholder' => 'Usuario'));?>
                        </div>
                        <div class="form-group">
                           <?php echo $this->Form->input('password',array('label' => 'Contraseña: ', 'class' => "form-control", 'autocomplete' => 'off', 'placeholder' => 'Password'));?>
                        </div>
                       
                          <?php echo $this->Form->submit('Ingresar',array('class'=>'btn btn-primary'));?>   
                    </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
     </form>
</div>
