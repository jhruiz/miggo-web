<?php $this->layout=false;?>
<legend><center><h4><?php echo __('Lista Reteica'); ?></h4></center></legend>
    <div class="table-responsive">
        <div class="container">        
            <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                    <th><?php echo h('Nombre'); ?></th>
                    <th><?php echo h('Porcentaje'); ?></th>
                    <th>&nbsp;</th>
                </tr>
                <?php foreach ($datosReteica as $rtica): ?>
                <tr>
                    <td><?php echo h($rtica['Reteicaretefuente']['descripcion']); ?>&nbsp;</td>
                    <td><?php echo h($rtica['Reteicaretefuente']['porcentaje']); ?>%</td>
                    <td><input type="checkbox" class="chkRtIca" data-id="<?php echo($rtica['Reteicaretefuente']['id']);?>" onclick="cantidadImp('chkRtIca')" value="<?php echo($rtica['Reteicaretefuente']['porcentaje']);?>"></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>    
<button id="btn_pagarcuenta" class="btn btn-primary center-block" onclick="aplicarReteica();">Aplicar</button>