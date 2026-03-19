<?php
App::uses('AppModel', 'Model');

class Iaconsulta extends AppModel {

    /**
     * Retorna el prompt solicitado desde javascript haciendo puente por la función del controlador
     */
    public function obtenerPrompt($input) {

        $prompts = [
            'cierre_diario' => "Eres el Consultor Experto de {$input['empresa']}. 
                                Tu función NO es resumir datos (el usuario ya tiene los totales), sino realizar un análisis crítico. 
                                Debes identificar riesgos (como gastos altos, cuentas sin especificar, cómo gestionar abonos, consejos de compras a credito), 
                                evaluar la liquidez según los medios de pago y dar 2 recomendaciones estratégicas de negocio. 
                                Usa un tono ejecutivo, directo y analítico pero sin ser agresivo.",
            
            'analisis_inventario' => "Eres un experto en logística para {$input['empresa']}. 
                                    Revisa estos movimientos de stock e identifica posibles fugas o falta de rotación: ",
            
            'perfil_cliente' => "Eres un experto en Marketing. Analiza el comportamiento de este cliente 
                                y sugiere una estrategia de fidelización personalizada: "
        ];

        return $prompts[$input['modulo']];
    }

}
