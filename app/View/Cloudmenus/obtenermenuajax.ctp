<?php echo ($this->Html->script('obtenermenu/obtenermenu.js')); ?>
<?php if (count($arrMenu) > '0'){?>
 <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
 <div class="menu_section">
 <h3>Menú</h3>
   <ul class="nav side-menu">
                <li><a href="<?php echo $urlPublica . '/usuarios/paginainicio'?>"><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                    <li><a href="index.html">Dashboard</a>
                    </li>
                    <li><a href="index2.html">Dashboard2</a>
                    </li>
                    <li><a href="index3.html">Dashboard3</a>
                    </li>
                  </ul>
                </li>
               <?php foreach ($arrMenu as $menu):  ?>
               <li><a><i class="<?php echo  $menu['data']['clase_icon']?>"></i>   <?php echo  $menu['data']['descripcion']?> <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                   
                     <?php foreach ($menu['submenu'] as $subMenu):  ?>                      
                        <li>                          
                          <a class="test" href="<?php echo $urlPublica . $subMenu['url']; ?>"> 
                          <?php echo ucfirst(strtolower($subMenu['descripcion'])); ?> 
                          <span class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo($subMenu['ayuda']);?>"></span>
                          <!-- test -->
                          </a>
                        </li>
                     <?php endforeach; ?>
                    
                  </ul>
                </li>
                <?php endforeach; ?>
              </ul>
            </div>
 </div>
  </div>
   <!-- /menu footer buttons -->
          <div class="">
          </div>
<?php } else {}?>
