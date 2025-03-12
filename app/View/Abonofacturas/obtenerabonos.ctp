<?php 
$this->layout=false;
echo $this->Html->css('abonos/obtenerabonos.css', array('rel' => 'stylesheet', 'media' => 'all'));
echo ($this->Html->script('abonos/gestionabonos.js'));
?>  


<div class="table-responsive">
    <div class="container">
        <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
        <tr>
                        <th><?php echo ('Cliente'); ?></th>
                        <th><?php echo ('Identificación'); ?></th>
                        <th><?php echo ('Usuairo'); ?></th>
                        <th><?php echo ('Cuenta'); ?></th>
                        <th><?php echo ('Fecha del Abono'); ?></th>
                        <th><?php echo ('Valor'); ?></th>
                        <th><?php echo ('Acciones'); ?></th>
        </tr>
        <?php $total = 0; ?>
        <?php foreach ($abonos as $abono): ?>
            <tr id="fila-<?php echo h($abono['Abonofactura']['id']); ?>">
                <td><?php echo h($abono['C']['nombre']); ?></td>
                <td><?php echo h($abono['C']['nit']); ?></td>
                <td><?php echo h($abono['U']['nombre']); ?></td>
                <td><?php echo h($abono['CU']['descripcion']); ?></td>
                <td><?php echo h($abono['Abonofactura']['created']); ?></td>
                <td class="valor"><?php echo h("$" . number_format($abono['Abonofactura']['valor'], 2)); ?></td>
                <td class="actions">
                    <i class="fa fa-pencil fa-lg text-primary" 
                        id="<?php echo h($abono['Abonofactura']['id']); ?>" 
                        data-valor="<?php echo($abono['Abonofactura']['valor']); ?>" 
                        data-cuenta="<?php echo($abono['CU']['id']); ?>" 
                        data-fecha="<?php echo($abono['Abonofactura']['created']); ?>" 
                        style="cursor: pointer;" onclick="setearEditarAbono(this);"></i>

                    <i class="fa fa fa-trash-o fa-lg text-danger" 
                        id="<?php echo h($abono['Abonofactura']['id']); ?>" 
                        data-valor="<?php echo($abono['Abonofactura']['valor']); ?>" 
                        data-cuenta="<?php echo($abono['CU']['id']); ?>"
                        data-prefactura="<?php echo($prefacturaId);?>"
                        style="cursor: pointer;" 
                        onclick="eliminarAbono(this);"></i>
                </td>
            </tr>
            <?php $total += $abono['Abonofactura']['valor']; ?>
            <?php endforeach; ?>
        <tr><td colspan="4"></td>
            <td><b>TOTAL</b></td>
            <td id="totalesAbonos"><?php echo h("$" . number_format($total, 2)); ?></td>
            <td>&nbsp;</td>
        </tr>
        </table>
    </div>
</div>

<div class="container d-flex justify-content-center align-items-center" style="width: 50%; margin: 0 auto; padding: 20px;" id="formEditarAbono">
        <h2 class="mt-4"><b>Datos del abono</b></h2>
        <form class="form">
            <!-- Campo para ingresar valores en pesos colombianos -->
            <div class="form-group mr-2">
                <label for="fechaAbono">Fecha del abono:</label>
                <input type="text" class="form-control form-control-sm" id="fechaAbono" disabled="disabled">
            </div>

            <!-- Input tipo select -->
            <div class="form-group mr-2">
                <label for="opciones">Cuenta:</label>
                <select class="form-control form-control-sm" id="cuenta">
                <?php foreach ($cuentas as $key => $val): ?>
                    <option value="<?php echo ($key); ?>"><?php echo ($val); ?></option>
                <?php endforeach;?>
                </select>
            </div>

            <!-- Campo para ingresar valores en pesos colombianos -->
            <div class="form-group mr-2">
                <label for="valorAbono">Valor del abono:</label>
                <input type="text" class="form-control form-control-sm" id="valorAbono" placeholder="Valor del abono">
            </div>
            
            <!-- Campo para el valor inicial del abono-->
            <div>
                <input type="hidden" id="valorAbonoHidden">
            </div>

            
            <!-- Campo para el id del abono-->
            <div>
                <input type="hidden" id="idAbono">
            </div>

            <!-- Campo para el id de la prefactura-->
            <div>
                <input type="hidden" id="idPrefactura" value="<?php echo($prefacturaId);?>">
            </div>
 

            <!-- Botón de actualizar y limpiar -->
            <div>
            <button type="button" class="btn btn-primary btn-sm" id="btnActAbono">Actualizar</button>
            <button type="button" class="btn btn-primary btn-sm" id="btnHide">Limpiar</button>
            </div>
        </form>
</div>
