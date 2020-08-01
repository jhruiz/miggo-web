<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: WorkFlow');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
        echo $this->Html->css(array('StyleLayout', 'StyleTable'));
        echo $this->Html->css('bootstrap');
        
        //echo $this->html->script('bootstrap');   jquery-ui-1.10.3.custom.css  jquery-ui-css
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        
        echo $this->Html->script('jquery-1.10.2');
         echo $this->Html->script('jquery-ui/js/jquery-ui-1.10.3.custom.min');
            echo $this->Html->css('jquery-ui-css/smoothness/jquery-ui.css');
            
            echo $this->Html->css('menu');
        echo $this->Html->script('menu');
        
        /*** Adicionamos librería para menu vertical **/
        echo $this->Html->script('menu_vert/jquery.easing.1.3');
        echo $this->Html->script('menu_vert/script_menu_vert');
        echo $this->Html->css('style_menu_vert');
        
        /*** Adicionamos funciones para mostrar modal ***/
//        echo $this->Html->script('modalCargar');
            
	?>
</head>
<body>
	<div id="container">
		
		<div id="content2" class="container-fluid">                        

			<?php echo $this->fetch('content'); ?>
		</div>		
	</div>
        <input type="hidden" id="url-proyecto" value="<?php echo $this->Html->url('/',true) ?>" />
</body>
</html>
