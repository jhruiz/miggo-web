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

    /**
     * Registrar consumos de IA por petición
     */
    public function registrarConsumo($empresaId, $modulo, $respuestaGoogle, $statusHttp) {
        // Extraer metadata de consumo
        $usage = isset($respuestaGoogle['usageMetadata']) ? $respuestaGoogle['usageMetadata'] : null;
        $candidate = isset($respuestaGoogle['candidates'][0]) ? $respuestaGoogle['candidates'][0] : null;

        // Precios Gemini 1.5 Flash (estimados por 1M de tokens)
        $p_input = 0.075 / 1000000;
        $p_output = 0.30 / 1000000;

        $tInput = $usage['promptTokenCount'] ?? 0;
        $tOutput = $usage['candidatesTokenCount'] ?? 0;
        $costo = ($tInput * $p_input) + ($tOutput * $p_output);

        $data = [
            'empresa_id' => $empresaId,
            'modelo' => $respuestaGoogle['modelVersion'] ?? 'gemini-1.5-flash',
            'endpoint' => $modulo,
            'tokens_prompt' => $tInput,
            'tokens_candidates' => $tOutput,
            'tokens_total' => $usage['totalTokenCount'] ?? 0,
            'costo_estimado_usd' => $costo,
            'status_http' => $statusHttp,
            'finish_reason' => $candidate['finishReason'] ?? 'STOP'
        ];

        $entidad = $this->newEntity($data);
        return $this->save($entidad);
    }

}
