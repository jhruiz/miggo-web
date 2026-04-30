<!DOCTYPE html>
<html>
<head>
    <meta charset="big5">
    <?php echo $this->Html->charset(); ?>
    <title>Miggo</title>
    <?php
    echo $this->Html->meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0', 'http-equiv' => "X-UA-Compatible"));
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

    echo $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array('rel' => 'stylesheet'));
    echo $this->Html->css('https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css', array('rel' => 'stylesheet'));
    echo $this->Html->script('https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js');
    echo $this->Html->script('https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-es-ES.min.js');

    echo $this->Html->script('menu');
    echo $this->Html->script('modalCargar');
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
    echo $this->Html->script('template/js/flot/jquery.flot.resize.js');
    echo $this->Html->script('template/js/echart/echarts-all.js');
    echo $this->Html->script('template/js/echart/green.js');
    echo $this->Html->script('template/js/bootstrap.min.js');
    ?>

    <style type="text/css">
        .nav-sm .container.body .col-md-3.left_col { position: absolute !important; }
        .body.nav-sm .container.body .left_col{ position: absolute !important; }
        .vertical-offset-100{ padding-top:10%; }

        /* Ocultar ruidos visuales */
        span[style*="background-color: rgb(153, 204, 0)"], 
        div[style*="background-color: white"],
        .alert:empty { display: none !important; }

        /* MEJORA DE CARDS (DASHBOARD) */
        .tile_stats_count {
            background: #ffffff !important;
            border: 1px solid #E6E9ED !important;
            border-left: 5px solid #2A3F54 !important;
            padding: 10px 15px !important;
            margin-bottom: 10px !important;
            border-radius: 4px !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
            min-height: 80px;
        }
        .tile_stats_count .count_top { font-size: 11px !important; color: #73879C !important; font-weight: bold !important; text-transform: uppercase !important; }
        .tile_stats_count b div { font-size: 24px !important; color: #2A3F54 !important; margin-top: 3px !important; }
        .alert-warning-miggo { border-left-color: #FA5858 !important; }
        .alert-warning-miggo b div, .alert-warning-miggo a { color: #FA5858 !important; }

        /* AJUSTE BARRA SUPERIOR */
        .nav_menu { margin-bottom: 0 !important; padding: 2px 0 !important; }
    </style>
</head>

<body class="nav-md">
    <?php if ($logged_in): ?>
    <div class="container body">
        <div class="main_container">

            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0; margin-bottom: 10px;">
                        <div class="profile">
                            <img src="/img/png/miggo.jpeg" alt="..." style="width: 100%; height: 100%;">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br />
                    <div id='menuUsr' style="margin-top: 60px;"></div>
                </div>
            </div>

            <div class="top_nav">
                <div class="nav_menu">
                    <nav class="" role="navigation">
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>
                        <ul class="nav navbar-nav navbar-right">
                            <li role="presentation">
                                <?php echo $this->Html->link("", array('controller' => 'usuarios', 'action' => 'logout'), array('class' => 'glyphicon glyphicon-off', 'style' => 'font-size:18px; padding:15px;')) ?>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="right_col" role="main">
                
                <div class="row tile_count">
                    <?php foreach ($ordenTrabajos as $ot): ?>
                        <div class="animated flipInY col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                            <span class="count_top">ORDEN <?php echo $ot['OE']['descripcion']; ?></span>
                            <div><b><?php echo (number_format($ot['0']['contador'], 0)); ?></b></div>
                        </div>
                    <?php endforeach;?>
                    
                    <div class="animated flipInY col-md-2 col-sm-4 col-xs-6 tile_stats_count <?php if ($arlerts > 0) echo 'alert-warning-miggo'; ?>">
                        <span class="count_top"><?php echo $this->Html->link(__('ALERTAS PENDIENTES'), array('controller' => 'alertaordenes', 'action' => 'index')); ?></span>
                        <div><b><?php echo (number_format($arlerts, 0)); ?></b></div>
                    </div>
                    <div class="animated flipInY col-md-2 col-sm-4 col-xs-6 tile_stats_count <?php if (count($eventos) > 0) echo 'alert-warning-miggo'; ?>">
                        <span class="count_top"><?php echo $this->Html->link(__('EVENTOS'), array('controller' => 'eventos', 'action' => 'index')); ?></span>
                        <div><b><?php echo (number_format(count($eventos), 0)); ?></b></div>
                    </div>
                    <div class="animated flipInY col-md-2 col-sm-4 col-xs-6 tile_stats_count <?php if (count($ctasXCobrar) > 0) echo 'alert-warning-miggo'; ?>">
                        <span class="count_top"><?php echo $this->Html->link(__('CUENTAS POR COBRAR'), array('controller' => 'cuentasclientes', 'action' => 'index')); ?></span>
                        <div><b><?php echo (number_format(count($ctasXCobrar), 0)); ?></b></div>
                    </div>
                    <div class="animated flipInY col-md-2 col-sm-4 col-xs-6 tile_stats_count <?php if (count($ctasXPagar) > 0) echo 'alert-warning-miggo'; ?>">
                        <span class="count_top"><?php echo $this->Html->link(__('CUENTAS POR PAGAR'), array('controller' => 'cuentaspendientes', 'action' => 'index')); ?></span>
                        <div><b><?php echo (number_format(count($ctasXPagar), 0)); ?></b></div>
                    </div>
                    <div class="animated flipInY col-md-2 col-sm-4 col-xs-6 tile_stats_count <?php if ($productosBajos['0']['0']['contador'] > 0) echo 'alert-warning-miggo'; ?>">
                        <span class="count_top"><?php echo $this->Html->link(__('PRODS. POR AGOTARSE'), array('controller' => 'cargueinventarios', 'action' => 'index')); ?></span>
                        <div><b><?php echo (number_format($productosBajos['0']['0']['contador'], 0)); ?></b></div>
                    </div>
                    <?php if($infoEmp['Empresa']['vercuentasdb'] == '1') { ?>
                        <div class="animated flipInY col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                            <span class="count_top">
                                <?php echo $this->Form->input('accounts', array('label' => '','type' => 'select','options' => $listCuentas,'id' => 'account')); ?>
                            </span>
                            <b><span class="value_account number">0</span></b>
                        </div>
                    <?php } ?>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <?php echo $this->fetch('content'); ?>
                    </div>
                </div>

                <footer>
                    <div class="pull-right"><a href="http://miggo.com.co/">MIGGO SOLUTIONS</a> - Todos los derechos reservados.</div>
                    <div class="clearfix"></div>
                </footer>
            </div>
        </div>
    </div>
    <?php else: ?>
        <?php echo $this->fetch('content'); ?>
    <?php endif; ?>

    <header>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-1 hidden-print">
                    <?php if ($logged_in): ?>
                        <input type="hidden" id="user-id" value="<?php echo $current_user['id'] ?>" />
                        <input type="hidden" id="tipoperfiluser_id" value="<?php echo $current_user['Perfile']['descripcion'] ?>" />
                        <input type="hidden" id="perfiluser_id" value="<?php echo $current_user['Perfile']['id'] ?>" />
                    <?php endif;?>
                </div>
            </div>
        </div>
    </header>
    <input class="hidden-print" type="hidden" id="url-proyecto" value="<?php echo $this->Html->url('/', true) ?>" />
</body>
</html>