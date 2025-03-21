<?php
App::uses('AppModel', 'Model');

class Alertaordene extends AppModel {

        public function guardaralerta($params){
            $data = array();
            $alertaorden = new Alertaordene();
            
            $data['alerta_id'] = $params['alertaId'];
            $data['unidadesmedida_id'] = $params['unidadMedidaId'];
            $data['ordentrabajo_id'] = $params['ordenTrabajoId'];
            $data['estadoalerta_id'] = $params['estadoAlertaId'];
            $data['fecha_alerta'] = $params['fechaAlerta'];
            $data['fecha_mantenimiento'] = $params['fechaMantto'];
            $data['km_prom_dia'] = $params['kmxDia'];
            $data['km_mantenimiento'] = $params['kmProxMantto'];
            $data['observaciones'] = $params['observaciones'];
            $data['cliente_id'] = $params['clienteId'];
            $data['canalventa_id'] = $params['canalventas'];

            // parametros crear alerta factura
            $data['factura_id'] = $params['facturaId'];
            // parametros crear alerta prefactura
            $data['prefactura_id'] = $params['prefacturaId'];
            // parametros crear alerta General
            $data['usuario_id'] = $params['usuarioId'];
            
            if($alertaorden->save($data)){                
                return '1';
            }else{
                return '0';
            }
                
        }

        public function obtenerInfoAlertaOrden($filtros){

            $arr_join = array(); 
            array_push($arr_join, array(
                'table' => 'ordentrabajos', 
                'alias' => 'O', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.ordentrabajo_id=O.id')                
            ));

            array_push($arr_join, array(
                'table' => 'vehiculos', 
                'alias' => 'VH', 
                'type' => 'LEFT',
                'conditions' => array('O.vehiculo_id=VH.id')                
            ));

            array_push($arr_join, array(
                'table' => 'usuarios', 
                'alias' => 'US', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.usuario_id=US.id')                
            ));

            array_push($arr_join, array(
                'table' => 'clientes', 
                'alias' => 'CL', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.cliente_id=CL.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'estadoalertas', 
                'alias' => 'EA', 
                'type' => 'INNER',
                'conditions' => array('EA.id=Alertaordene.estadoalerta_id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'unidadesmedidas', 
                'alias' => 'UM', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.unidadesmedida_id=UM.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'alertas', 
                'alias' => 'AL', 
                'type' => 'INNER',
                'conditions' => array('Alertaordene.alerta_id=AL.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'plantaservicios', 
                'alias' => 'PS', 
                'type' => 'LEFT',
                'conditions' => array('O.plantaservicio_id=PS.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'ordenestados', 
                'alias' => 'OE', 
                'type' => 'LEFT',
                'conditions' => array('O.ordenestado_id=OE.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'canalventas', 
                'alias' => 'CV', 
                'type' => 'LEFT',
                'conditions' => array('CV.id=Alertaordene.canalventa_id')                
            ));
            
            $alertasOrdenes = $this->find('all', array(                
                'joins' => $arr_join, 
                'fields' => array(
                    'O.id',
                    'O.kilometraje',
                    'O.usuario_id',
                    'O.fecha_ingreso',
                    'O.fecha_salida',
                    'O.soat',
                    'O.tecnomecanica',
                    'EA.id',
                    'EA.descripcion',
                    'Alertaordene.id',
                    'Alertaordene.created',
                    'Alertaordene.fecha_alerta',
                    'Alertaordene.fecha_mantenimiento',
                    'Alertaordene.fecha_ultima_llamada',
                    'Alertaordene.cant_llamadas',
                    'Alertaordene.observaciones',
                    'VH.id',
                    'VH.placa',
                    'VH.linea',
                    'VH.modelo',
                    'US.id',
                    'US.nombre',
                    'CV.descripcion',
                    'CL.id',
                    'CL.nit',
                    'CL.nombre',
                    'CL.direccion',
                    'CL.celular',
                    'CL.cumpleanios',
                    'UM.*',
                    'AL.*',
                    'PS.*',
                    'OE.*'
                ),                             
                'conditions' => $filtros,
                'recursive' => '-1',
                'order' => 'Alertaordene.id DESC' 
                ));            
            
            return $alertasOrdenes;            
        }
        public function obtenerInfoAlertaFactura($filtros){

            $arr_join = array(); 

            array_push($arr_join, array(
                'table' => 'usuarios', 
                'alias' => 'US', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.usuario_id=US.id')                
            ));

            array_push($arr_join, array(
                'table' => 'clientes', 
                'alias' => 'CL', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.cliente_id=CL.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'estadoalertas', 
                'alias' => 'EA', 
                'type' => 'INNER',
                'conditions' => array('EA.id=Alertaordene.estadoalerta_id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'unidadesmedidas', 
                'alias' => 'UM', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.unidadesmedida_id=UM.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'alertas', 
                'alias' => 'AL', 
                'type' => 'INNER',
                'conditions' => array('Alertaordene.alerta_id=AL.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'canalventas', 
                'alias' => 'CV', 
                'type' => 'LEFT',
                'conditions' => array('CV.id=Alertaordene.canalventa_id')                
            ));
            
            
            $alertasFacturas = $this->find('all', array(                
                'joins' => $arr_join, 
                'fields' => array(
                    'EA.id',
                    'EA.descripcion',
                    'Alertaordene.*',
                    'US.id',
                    'US.nombre',
                    'CV.descripcion',
                    'CL.id',
                    'CL.nit',
                    'CL.nombre',
                    'CL.direccion',
                    'CL.celular',
                    'CL.cumpleanios',
                    'UM.*',
                    'AL.*',
                ),                             
                'conditions' => $filtros,
                'recursive' => '-1',
                'order' => 'Alertaordene.fecha_alerta ASC' 
                ));            
            
            return $alertasFacturas;            
        }
        public function obtenerInfoAlertaPreFactura($filtros){

            $arr_join = array(); 
            array_push($arr_join, array(
                'table' => 'prefacturas', 
                'alias' => 'F', 
                'type' => 'LEFT',
                'conditions' => array('F.id=Alertaordene.prefactura_id')                
            ));

            array_push($arr_join, array(
                'table' => 'usuarios', 
                'alias' => 'US', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.usuario_id=US.id')                
            ));

            array_push($arr_join, array(
                'table' => 'clientes', 
                'alias' => 'CL', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.cliente_id=CL.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'estadoalertas', 
                'alias' => 'EA', 
                'type' => 'INNER',
                'conditions' => array('EA.id=Alertaordene.estadoalerta_id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'unidadesmedidas', 
                'alias' => 'UM', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.unidadesmedida_id=UM.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'alertas', 
                'alias' => 'AL', 
                'type' => 'INNER',
                'conditions' => array('Alertaordene.alerta_id=AL.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'canalventas', 
                'alias' => 'CV', 
                'type' => 'LEFT',
                'conditions' => array('CV.id=Alertaordene.canalventa_id')                
            ));          
            
            $alertasPreFacturas = $this->find('all', array(                
                'joins' => $arr_join, 
                'fields' => array(
                    
                    'EA.id',
                    'EA.descripcion',
                    'Alertaordene.*',
                    'US.nombre',
                    'CV.descripcion',
                    'CL.id',
                    'CL.nit',
                    'CL.nombre',
                    'CL.direccion',
                    'CL.celular',
                    'CL.cumpleanios',
                    'UM.*',
                    'AL.*',
                ),                             
                'conditions' => $filtros,
                'recursive' => '-1',
                'order' => 'Alertaordene.fecha_alerta ASC' 
                ));            
            
            return $alertasPreFacturas;            
        }

        public function obtenerInfoAlerta($id){
            $alerta = $this->find(
                'first', array(
                    'conditions' => array(
                        'Alertaordene.id' => $id)));
            return $alerta;
        }

        public function actualizarAlerta($data){
            $alertaorden = new Alertaordene();

            if($alertaorden->save($data)){                
                return '1';
            }else{
                return '0';
            }
        }

        public function obtenerAlertaOrdenSoat($clienteId, $vehiculoId, $fechaSoat, $estadoIni, $alertaId){
            $alerta = $this->find('first', array(
                'conditions' => array(
                    'Alertaordene.cliente_id' => $clienteId,
                    'Alertaordene.vehiculo_id' => $vehiculoId,
                    'Alertaordene.fecha_mantenimiento' => $fechaSoat,
                    'Alertaordene.estadoalerta_id' => $estadoIni,
                    'Alertaordene.alerta_id' => $alertaId
                )));

            return $alerta;
        }

        public function obtnerAlertasRenovacionDocs($filtros){
            $arr_join = array(); 

            array_push($arr_join, array(
                'table' => 'vehiculos', 
                'alias' => 'VH', 
                'type' => 'INNER',
                'conditions' => array('Alertaordene.vehiculo_id=VH.id')                
            ));

            array_push($arr_join, array(
                'table' => 'clientes', 
                'alias' => 'CL', 
                'type' => 'INNER',
                'conditions' => array('Alertaordene.cliente_id=CL.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'estadoalertas', 
                'alias' => 'EA', 
                'type' => 'INNER',
                'conditions' => array('EA.id=Alertaordene.estadoalerta_id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'unidadesmedidas', 
                'alias' => 'UM', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.unidadesmedida_id=UM.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'alertas', 
                'alias' => 'AL', 
                'type' => 'INNER',
                'conditions' => array('Alertaordene.alerta_id=AL.id')                
            ));
            
            $alertasOrdenes = $this->find('all', array(
                'joins' => $arr_join, 
                'fields' => array(
                    'EA.id',
                    'EA.descripcion',
                    'Alertaordene.id',
                    'Alertaordene.created',
                    'Alertaordene.fecha_alerta',
                    'Alertaordene.fecha_mantenimiento',
                    'Alertaordene.fecha_ultima_llamada',
                    'Alertaordene.cant_llamadas',
                    'Alertaordene.observaciones',
                    'VH.id',
                    'VH.placa',
                    'VH.linea',
                    'VH.modelo',
                    'CL.id',
                    'CL.nit',
                    'CL.nombre',
                    'CL.direccion',
                    'CL.celular',
                    'UM.*',
                    'AL.*'
                ),                             
                'conditions' => $filtros,
                'recursive' => '-1',
                'order' => 'Alertaordene.fecha_alerta DESC' 
                ));            
            
            return $alertasOrdenes;             
        }
        public function obtnerAlertasFacturaRenovacionDocs($filtros){
            $arr_join = array(); 

            array_push($arr_join, array(
                'table' => 'vehiculos', 
                'alias' => 'VH', 
                'type' => 'INNER',
                'conditions' => array('Alertaordene.vehiculo_id=VH.id')                
            ));

            array_push($arr_join, array(
                'table' => 'clientes', 
                'alias' => 'CL', 
                'type' => 'INNER',
                'conditions' => array('Alertaordene.cliente_id=CL.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'estadoalertas', 
                'alias' => 'EA', 
                'type' => 'INNER',
                'conditions' => array('EA.id=Alertaordene.estadoalerta_id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'unidadesmedidas', 
                'alias' => 'UM', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.unidadesmedida_id=UM.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'alertas', 
                'alias' => 'AL', 
                'type' => 'INNER',
                'conditions' => array('Alertaordene.alerta_id=AL.id')                
            ));
            
            $alertasOrdenes = $this->find('all', array(
                'joins' => $arr_join, 
                'fields' => array(
                    'EA.id',
                    'EA.descripcion',
                    'Alertaordene.id',
                    'Alertaordene.created',
                    'Alertaordene.fecha_alerta',
                    'Alertaordene.fecha_mantenimiento',
                    'Alertaordene.fecha_ultima_llamada',
                    'Alertaordene.cant_llamadas',
                    'Alertaordene.observaciones',
                    'VH.id',
                    'VH.placa',
                    'VH.linea',
                    'VH.modelo',
                    'CL.id',
                    'CL.nit',
                    'CL.nombre',
                    'CL.direccion',
                    'CL.celular',
                    'UM.*',
                    'AL.*'
                ),                             
                'conditions' => $filtros,
                'recursive' => '-1',
                'order' => 'Alertaordene.id DESC' 
                ));            
            
            return $alertasOrdenes;             
        }


        public function obtieneAlertaOrdenesTortas($empresaId, $fechaInicial = null, $fechaFinal = null){

            $filterDate = '';
            if(!empty($fechaInicial) && !empty($fechaFinal)){
                $filterDate = array('Alertaordene.fecha_alerta BETWEEN ? AND ? ' => array($fechaInicial, $fechaFinal));
            }

            $arr_join = array();

            array_push($arr_join, array(
                'table' => 'alertas', 
                'alias' => 'A', 
                'type' => 'INNER',
                'conditions' => array('Alertaordene.alerta_id=A.id')                
            ));            

            $resp = $this->find('all', array(
                'joins' => $arr_join,
                'fields' => array(
                    'count(Alertaordene.alerta_id) as contador',
                    'A.descripcion'
                ),
                'conditions' => array(
                    'A.empresa_id' => $empresaId,
                    $filterDate
                ),
                'group' => 'Alertaordene.alerta_id'
            ));

            return $resp;        

        }

        public function obtenerAlertasRangoFechas($initDate, $endDate, $empresaId){
            $arr_join = array();

            array_push($arr_join, array(
                'table' => 'alertas', 
                'alias' => 'A', 
                'type' => 'INNER',
                'conditions' => array('Alertaordene.alerta_id=A.id')                
            ));            

            $resp = $this->find('all', array(
                'joins' => $arr_join,
                'conditions' => array(
                    'Alertaordene.fecha_alerta BETWEEN ? AND ?' => array($initDate, $endDate),
                    'A.empresa_id' => $empresaId
                ),
            ));

            return $resp;
        }

        public function obtenerAlertasEmpresaFecha($initDate, $endDate, $empresaId, $tipo){

            $tipAlert = $tipo == '1' ? 'Alertaordene.ordentrabajo_id is null' :  'Alertaordene.ordentrabajo_id is not null';
            $arr_join = array(); 

            array_push($arr_join, array(
                'table' => 'vehiculos', 
                'alias' => 'VH', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.vehiculo_id=VH.id')                
            ));

            array_push($arr_join, array(
                'table' => 'clientes', 
                'alias' => 'CL', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.cliente_id=CL.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'estadoalertas', 
                'alias' => 'EA', 
                'type' => 'INNER',
                'conditions' => array('EA.id=Alertaordene.estadoalerta_id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'unidadesmedidas', 
                'alias' => 'UM', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.unidadesmedida_id=UM.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'alertas', 
                'alias' => 'AL', 
                'type' => 'INNER',
                'conditions' => array('Alertaordene.alerta_id=AL.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'ordentrabajos', 
                'alias' => 'OT', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.ordentrabajo_id=OT.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'usuarios', 
                'alias' => 'US', 
                'type' => 'LEFT',
                'conditions' => array('OT.usuario_id=US.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'clientes', 
                'alias' => 'CLI', 
                'type' => 'LEFT',
                'conditions' => array('OT.cliente_id=CLI.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'vehiculos', 
                'alias' => 'VEHI', 
                'type' => 'LEFT',
                'conditions' => array('OT.vehiculo_id=VEHI.id')                
            ));
            
            $alertasOrdenes = $this->find('all', array(
                'joins' => $arr_join, 
                'fields' => array(
                    'EA.id',
                    'EA.descripcion',
                    'Alertaordene.id',
                    'Alertaordene.created',
                    'Alertaordene.fecha_alerta',
                    'Alertaordene.fecha_mantenimiento',
                    'Alertaordene.fecha_ultima_llamada',
                    'Alertaordene.cant_llamadas',
                    'Alertaordene.observaciones',
                    'VH.id',
                    'VH.placa',
                    'VH.linea',
                    'VH.modelo',
                    'CL.id',
                    'CL.nit',
                    'CL.nombre',
                    'CL.direccion',
                    'CL.celular',
                    'UM.*',
                    'AL.*',
                    'US.id',
                    'US.nombre',
                    'CLI.*',
                    'VEHI.*'
                ),                             
                'conditions' => array(
                    'Alertaordene.fecha_alerta BETWEEN ? AND ? ' => array($initDate, $endDate),
                    'AL.empresa_id' => $empresaId,
                    $tipAlert
                ),
                'recursive' => '-1',
                'order' => 'Alertaordene.id DESC' 
                ));            
            
            return $alertasOrdenes;              
        }


        public function obtieneEstadoAlertaTortas($empresaId){

            $arr_join = array();

            array_push($arr_join, array(
                'table' => 'estadoalertas', 
                'alias' => 'EA', 
                'type' => 'INNER',
                'conditions' => array('Alertaordene.estadoalerta_id=EA.id')                
            ));            

            $resp = $this->find('all', array(
                'joins' => $arr_join,
                'fields' => array(
                    'count(Alertaordene.estadoalerta_id) as contador',
                    'EA.descripcion'
                ),
                'conditions' => array(
                    'EA.empresa_id' => $empresaId,
                    'EA.final' => '1'
                ),
                'group' => 'Alertaordene.estadoalerta_id'
            ));

            return $resp;        

        }        


        public function obtenerInfoAlertaFacturaEdit($filtros, $id){

            $arr_join = array(); 
          
            array_push($arr_join, array(
                'table' => 'clientes', 
                'alias' => 'CL', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.cliente_id=CL.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'estadoalertas', 
                'alias' => 'EA', 
                'type' => 'INNER',
                'conditions' => array('EA.id=Alertaordene.estadoalerta_id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'unidadesmedidas', 
                'alias' => 'UM', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.unidadesmedida_id=UM.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'alertas', 
                'alias' => 'AL', 
                'type' => 'INNER',
                'conditions' => array('Alertaordene.alerta_id=AL.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'plantaservicios', 
                'alias' => 'PS', 
                'type' => 'LEFT',
                'conditions' => array('O.plantaservicio_id=PS.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'ordenestados', 
                'alias' => 'OE', 
                'type' => 'LEFT',
                'conditions' => array('O.ordenestado_id=OE.id')                
            ));
            
            $alertasOrdenes = $this->find('all', array(                
                'joins' => $arr_join, 
                'fields' => array(
                   
                    'EA.id',
                    'EA.descripcion',
                    'Alertaordene.*',
                    'US.nombre',
                    'CL.id',
                    'CL.nit',
                    'CL.nombre',
                    'CL.direccion',
                    'CL.celular',
                    'CL.cumpleanios',
                    'UM.*',
                    'AL.*',
                    'PS.*',
                    'OE.*'
                ),                             
                'conditions' => $filtros,
                'recursive' => '-1',
                'order' => 'Alertaordene.id DESC' 
                ));            
            
            return $alertasOrdenes;            
        }
        public function obtenerInfoAlertaPreFacturaEdit($filtros){

            $arr_join = array(); 
            array_push($arr_join, array(
                'table' => 'ordentrabajos', 
                'alias' => 'O', 
                'type' => 'LEFT',
                'conditions' => array('O.id=Alertaordene.ordentrabajo_id')                
            ));

            array_push($arr_join, array(
                'table' => 'usuarios', 
                'alias' => 'US', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.usuario_id=US.id')                
            ));

            array_push($arr_join, array(
                'table' => 'clientes', 
                'alias' => 'CL', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.cliente_id=CL.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'estadoalertas', 
                'alias' => 'EA', 
                'type' => 'INNER',
                'conditions' => array('EA.id=Alertaordene.estadoalerta_id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'unidadesmedidas', 
                'alias' => 'UM', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.unidadesmedida_id=UM.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'alertas', 
                'alias' => 'AL', 
                'type' => 'INNER',
                'conditions' => array('Alertaordene.alerta_id=AL.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'plantaservicios', 
                'alias' => 'PS', 
                'type' => 'LEFT',
                'conditions' => array('O.plantaservicio_id=PS.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'ordenestados', 
                'alias' => 'OE', 
                'type' => 'LEFT',
                'conditions' => array('O.ordenestado_id=OE.id')                
            ));
            
            $alertasOrdenes = $this->find('all', array(                
                'joins' => $arr_join, 
                'fields' => array(
                    'EA.id',
                    'EA.descripcion',
                    'Alertaordene.*',
                    'US.nombre',
                    'CL.id',
                    'CL.nit',
                    'CL.nombre',
                    'CL.direccion',
                    'CL.celular',
                    'CL.cumpleanios',
                    'UM.*',
                    'AL.*',
                    'PS.*',
                    'OE.*'
                ),                             
                'conditions' => $filtros,
                'recursive' => '-1',
                'order' => 'Alertaordene.id DESC' 
                ));            
            
            return $alertasOrdenes;            
        }
        public function obtenerInfoAlertaGeneralEdit($filtros){

            $arr_join = array(); 
            array_push($arr_join, array(
                'table' => 'ordentrabajos', 
                'alias' => 'O', 
                'type' => 'LEFT',
                'conditions' => array('O.id=Alertaordene.ordentrabajo_id')                
            ));


            array_push($arr_join, array(
                'table' => 'clientes', 
                'alias' => 'CL', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.cliente_id=CL.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'estadoalertas', 
                'alias' => 'EA', 
                'type' => 'INNER',
                'conditions' => array('EA.id=Alertaordene.estadoalerta_id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'unidadesmedidas', 
                'alias' => 'UM', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.unidadesmedida_id=UM.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'alertas', 
                'alias' => 'AL', 
                'type' => 'INNER',
                'conditions' => array('Alertaordene.alerta_id=AL.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'plantaservicios', 
                'alias' => 'PS', 
                'type' => 'LEFT',
                'conditions' => array('O.plantaservicio_id=PS.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'ordenestados', 
                'alias' => 'OE', 
                'type' => 'LEFT',
                'conditions' => array('O.ordenestado_id=OE.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'canalventas', 
                'alias' => 'CV', 
                'type' => 'LEFT',
                'conditions' => array('CV.id=Alertaordene.canalventa_id')                
            ));
            
            $alertasOrdenes = $this->find('all', array(                
                'joins' => $arr_join, 
                'fields' => array(
                    'EA.id',
                    'EA.descripcion',
                    'Alertaordene.*',
                    'CL.id',
                    'CL.nit',
                    'CL.nombre',
                    'CL.direccion',
                    'CL.celular',
                    'CL.cumpleanios',
                    'CV.descripcion',
                    'UM.*',
                    'AL.*',
                    'PS.*',
                    'OE.*'
                ),                             
                'conditions' => $filtros,
                'recursive' => '-1',
                'order' => 'Alertaordene.id DESC' 
                ));            
            
            return $alertasOrdenes;            
        }

        public function obtenerInfoAlertaGeneral($filtros){

            $arr_join = array(); 

            array_push($arr_join, array(
                'table' => 'usuarios', 
                'alias' => 'US', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.usuario_id=US.id')                
            ));

            array_push($arr_join, array(
                'table' => 'clientes', 
                'alias' => 'CL', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.cliente_id=CL.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'estadoalertas', 
                'alias' => 'EA', 
                'type' => 'INNER',
                'conditions' => array('EA.id=Alertaordene.estadoalerta_id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'unidadesmedidas', 
                'alias' => 'UM', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.unidadesmedida_id=UM.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'alertas', 
                'alias' => 'AL', 
                'type' => 'INNER',
                'conditions' => array('Alertaordene.alerta_id=AL.id')                
            ));
            
            array_push($arr_join, array(
                'table' => 'canalventas', 
                'alias' => 'CV', 
                'type' => 'INNER',
                'conditions' => array('CV.id=Alertaordene.canalventa_id')                
            ));
            
            $alertasGeneral = $this->find('all', array(                
                'joins' => $arr_join, 
                'fields' => array(
                   
                    'EA.id',
                    'EA.descripcion',
                    'Alertaordene.*',
                    'CL.nit',
                    'CL.nombre',
                    'CL.direccion',
                    'CL.celular',
                    'CL.cumpleanios',
                    'UM.*',
                    'AL.*',
                    'US.nombre',
                    'CV.descripcion'
                ),                             
                'conditions' => $filtros,
                'recursive' => '-1',
                'order' => 'Alertaordene.fecha_alerta ASC' 
                ));            
            
            return $alertasGeneral;            
        }
}
