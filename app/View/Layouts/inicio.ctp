<!DOCTYPE html>
<html>
    <head><meta charset="big5">
      <?php echo $this->Html->charset(); ?>        
      <title>Miggo</title>        
      <?php
        echo $this->Html->meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0',  'http-equiv' => "X-UA-Compatible"));
        echo $this->Html->meta('icon');
        
        echo $this->Html->css(array('StyleLayout', 'StyleTable'));
        echo $this->Html->css('bootstrap.min.css', array('rel' => 'stylesheet', 'media' => 'all'));

        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');

        echo $this->Html->script('jquery-1.10.2');
        echo $this->Html->script('bootstrap.min'); 
        echo $this->Html->script('qrcode/jquery-qrcode-0.17.0.js');
        echo $this->Html->script('qrcode/jquery-qrcode-0.17.0.min.js');        
        echo $this->Html->script('qr/jquery.classyqr.js');
        echo $this->Html->script('qr/jquery.classyqr.min.js');        
        echo $this->Html->script('jquery-ui/js/jquery-ui-1.10.3.custom.min');
        echo $this->Html->script('bootbox.min.js');
        echo $this->Html->css('jquery-ui-css/redmond/jquery-ui.css');
        /** Adicionamos la librer赤a para el menu * */
        echo $this->Html->script('menu');

        /** Adicionamos funciones para mostrar modal ** */
        echo $this->Html->script('modalCargar');

        /** Adicionamos funciones utiles para html** */
        echo $this->Html->script('jquery_number/jquery.number');
        echo $this->Html->script('utilsjs/utilsElementosHTML');
        echo $this->Html->script('layout/inicio');

        echo $this->Html->css('template/css/bootstrap.min.css', array('rel' => 'stylesheet', 'media' => 'all'));
        echo $this->Html->css('template/fonts/css/font-awesome.min.css', array('rel' => 'stylesheet', 'media' => 'all'));
        echo $this->Html->css('template/css/animate.min.css', array('rel' => 'stylesheet', 'media' => 'all'));
        echo $this->Html->css('template/css/custom.css', array('rel' => 'stylesheet', 'media' => 'all'));
        echo $this->Html->css('template/css/maps/jquery-jvectormap-2.0.3.css', array('rel' => 'stylesheet', 'media' => 'all'));
        echo $this->Html->css('template/css/icheck/flat/green.css', array('rel' => 'stylesheet', 'media' => 'all'));
        echo $this->Html->css('template/css/floatexamples.css', array('rel' => 'stylesheet', 'media' => 'all'));

        echo $this->Html->script('template/js/gauge/gauge.min.js');
        echo $this->Html->script('template/js/gauge/gauge_demo.js');

        echo $this->Html->script('template/js/progressbar/bootstrap-progressbar.min.js');
        echo $this->Html->script('template/js/nicescroll/jquery.nicescroll.min.js');

        echo $this->Html->script('template/js/icheck/icheck.min.js');

        echo $this->Html->script('template/js/moment/moment.min.js');
        echo $this->Html->script('template/js/datepicker/daterangepicker.js');

        echo $this->Html->script('template/js/chartjs/chart.min.js');
        echo $this->Html->script('template/js/custom.js');

        echo $this->Html->script('template/js/flot/jquery.flot.js');
        echo $this->Html->script('template/js/flot/jquery.flot.pie.js');
        echo $this->Html->script('template/js/flot/jquery.flot.orderBars.js');
        echo $this->Html->script('template/js/flot/jquery.flot.time.min.js');
        echo $this->Html->script('template/js/flot/jquery.flot.spline.js');
        echo $this->Html->script('template/js/flot/jquery.flot.stack.js');
        echo $this->Html->script('template/js/flot/curvedLines.js');
        echo $this->Html->script('template/js/flot/jquery.flot.resize.js');

        echo $this->Html->script('template/js/echart/echarts-all.js');
        echo $this->Html->script('template/js/echart/green.js');
        echo $this->Html->script('template/js/bootstrap.min.js');
      ?>    

<style type="text/css">   

.nav-sm .container.body .col-md-3.left_col {
    position: absolute !important;
}
.body.nav-sm .container.body .left_col{
  position: absolute !important;
}

.vertical-offset-100{
    padding-top:10%;
}

.panel-default>.panel-heading {
  border-color: #000000 !important;
}

.panel-default {
    border-color: #000000 !important;
}

.cac_title{
  background-color: rgba(0,0,0,0.5) !important;
  text-align: center !important;
}

.cac{
  color: #ff0000 !important;
  margin-top: auto;
  margin-bottom: auto;
  width: 400px;
  border-color: 000000;
  background-color: rgba(0,0,0,0.5) !important;
}

.bg { 
  /* The image used */
  background-image: url("/img/login.jpg");

  /* Full height */
  height: 1000px; 

  /* Center and scale the image nicely */
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
} 

.vcenter{

    display: inline-block;
    vertical-align: middle;
    float: none;
}


</style>
       

    </head>

    <body class="nav-md">
           <?php if($logged_in){ ?>
        <div class="container body">
             <div class="main_container">

      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">

            <div class="navbar nav_title" style="border: 0; margin-bottom: 10px;">
                <div class="profile">
                <img src="/img/png/miggo.jpeg" alt="..." style="width: 100%; height: 100%;">
                </div>
            </div>
        <div class="clearfix">
            
        </div>

          <!-- menu prile quick info -->
          <!-- /menu prile quick info -->

          <br />

          <!-- sidebar menu -->
          <?php         
                    if($logged_in){                    
                    ?>
                        <div id='menuUsr' style="margin-top: 60px;">
                        </div>

                    <?php
                    }
                    ?>   
          <!-- /sidebar menu -->

         
        </div>
      </div>

      <!-- top navigation -->
      <div class="top_nav">

        <div class="nav_menu">
          <nav class="" role="navigation">
            <div class="nav toggle">
              <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
              <li class="">
                <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                  <li><a href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                </ul>
              </li>
              
              <li role="presentation" class="dropdown">
                  <?php if ($logged_in): ?>    
                <?php echo $this->Html->link("", array('controller' => 'usuarios','action'=> 'logout'), array( 'class' => 'glyphicon glyphicon-off'),array( 'data-toggle' => 'tooltip'))?> 
                <?php endif; ?> 
              </li>

            </ul>
          </nav>
        </div>

      </div>
      <!-- /top navigation -->


      <!-- page content -->
      <div class="right_col" role="main">

        <!-- top tiles -->
        <div>
         <!--ANIMATEE 006-->

          <?php foreach ($ordenTrabajos as $ot): ?>
            <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
              <div class="left"></div>
              <div class="right">
                <span class="count_top">
                <?php echo( 'ORDEN ' . $ot['OE']['descripcion']); ?>
                </span>
                <div style="font-size:30px;"><b><?php echo(number_format($ot['0']['contador'],0));?></b></div>
              </div>
            </div>
          <?php endforeach; ?>

            <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count" style="<?php if($arlerts>0){ echo "color:#FA5858;"; }?>">
              <div class="left"></div>
              <div class="right">
                <span class="count_top">
                <?php echo $this->Html->link(__('ALERTAS PENDIENTES'), array('controller' => 'alertaordenes', 'action' => 'index')); ?>                
                </span>
                <div style="font-size:30px;"><b>
                <?php echo(number_format($arlerts,0));?></b></div>
              </div>
            </div>

            <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count" style="<?php if(count($eventos)>0){ echo "color:#FA5858;"; }?>">
              <div class="left"></div>
              <div class="right">
                <span class="count_top">
                <?php echo $this->Html->link(__('EVENTOS'), array('controller' => 'eventos', 'action' => 'index')); ?>
                </span>
                <div style="font-size:30px;"><b>
                <?php echo(number_format(count($eventos),0));?></b></div>
              </div>
            </div>

            <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count" style="<?php if(count($ctasXCobrar)>0){ echo "color:#FA5858;"; }?>">
              <div class="left"></div>
              <div class="right">
                <span class="count_top">
                <?php echo $this->Html->link(__('CUENTAS POR COBRAR'), array('controller' => 'cuentasclientes', 'action' => 'index')); ?>
                </span>
                <div style="font-size:30px;"><b>
                <?php echo(number_format(count($ctasXCobrar),0));?></b></div>
              </div>
            </div>

            <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count" style="<?php if(count($ctasXPagar)>0){ echo "color:#FA5858;"; }?>">
              <div class="left"></div>
              <div class="right">
                <span class="count_top">
                <?php echo $this->Html->link(__('CUENTAS POR PAGAR'), array('controller' => 'cuentaspendientes', 'action' => 'index')); ?>
                </span>
                <div style="font-size:30px;"><b>
                <?php echo(number_format(count($ctasXPagar),0));?></b></div>
              </div>
            </div>

            <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count" style="<?php if($productosBajos['0']['0']['contador']>0){ echo "color:#FA5858;"; }?>">
              <div class="left"></div>
              <div class="right">
                <span class="count_top">
                <?php echo $this->Html->link(__('PRODS. POR AGOTARSE'), array('controller' => 'cargueinventarios', 'action' => 'index')); ?>
                </span>
                <div style="font-size:30px;"><b>
                <?php echo(number_format($productosBajos['0']['0']['contador'],0));?></b></div>
              </div>
            </div>

            <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
              <div class="left"></div>
              <div class="right">
                <span class="count_top">

                <!-- Listado de cuentas -->
                <?php echo $this->Form->input('accounts', array(
                  'label' => '', 
                  'type' => 'select', 
                  'options' => $listCuentas, 
                  'id' => 'account',
                  'style' => 'width:150px;'
                  ));?>   

                </span>
                <b><div style="font-size:30px;" class="value_account number">0</div></b>
              </div>
            </div>

            <?php for($i = 0; $i < $arrColMd; $i++){ ?>
              <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
                <div class="left"></div>
                <div class="right">
                  <span class="count_top"> 

                  </span>
                </div>            
              </div>            
            <?php } ?>          
          
        <!-- /top tiles -->

        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <?php echo $this->fetch('content'); ?>
          </div>

          <footer>
            <div class="copyright-info">

              <p class="pull-right">TORQUE RACING - Todos los derechos reservados <a href="https://colorlib.com">TORQUE RACING</a>  
              </p>
            </div>
            <div class="clearfix"></div>
          </footer>        
        </div>
      </div>
      <!-- /page content -->

    </div>
        </div>

        <header>
            <div class="container-fluid">
                <div class="row">
                                                       
                    <div class="col-xs-4 col-sm-4 col-md-1 hidden-print">                        
                        <?php if ($logged_in): ?>                        
                        <input type="hidden" id="user-id" value="<?php echo $current_user['id'] ?>" />
                        <input type="hidden" id="tipoperfiluser_id" value="<?php echo $current_user['Perfile']['descripcion'] ?>" />  
                        <input type="hidden" id="perfiluser_id" value="<?php echo $current_user['Perfile']['id'] ?>" />  
                        <?php endif; ?>                        
                    </div> 
                </div>
            </div>
        </header>
       
        <input class="hidden-print" type="hidden" id="url-proyecto" value="<?php echo $this->Html->url('/', true) ?>" />
    <?php
    }
    ?> 

                      <!-- sidebar menu -->
          <?php         
                    if(!$logged_in){                    
                    ?>
   
       
             <?php if ($flash = $this->Session->flash()) { ?>
                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">℅</span>
                  </button>
                  <strong>Atenci車n.</strong> <?php echo $flash ?>.
                </div>
                    <?php } ?>
            
                  
        <?php echo $this->fetch('content'); ?>     
              
                    <?php
                    }
                    ?>    
    </body>
</html>
