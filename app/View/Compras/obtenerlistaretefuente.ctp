<?php $this->layout=false;?>
<legend><center><h4><?php echo __('Lista Retefuente'); ?></h4></center></legend>
    <div class="table-responsive">
        <div class="container">        
            <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                    <th><?php echo h('Nombre'); ?></th>
                    <th><?php echo h('Porcentaje'); ?></th>
                    <th>&nbsp;</th>
                </tr>
                <?php foreach ($datosRetefuente as $rtfte): ?>
                <tr>
                    <td><?php echo h($rtfte['Reteicaretefuente']['descripcion']); ?>&nbsp;</td>
                    <td><?php echo h($rtfte['Reteicaretefuente']['porcentaje']); ?>%</td>
                    <td><input type="checkbox" class="chkRtfte" data-id="<?php echo($rtfte['Reteicaretefuente']['id']);?>" onclick="cantidadImp('chkRtfte')" value="<?php echo($rtfte['Reteicaretefuente']['porcentaje']);?>"></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>    
<button id="btn_pagarcuenta" class="btn btn-primary center-block" onclick="aplicarRetefuente();">Aplicar</button>