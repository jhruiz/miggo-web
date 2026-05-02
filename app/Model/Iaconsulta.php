<?php
App::uses('AppModel', 'Model');

class Iaconsulta extends AppModel {

    /**
     * Retorna el prompt solicitado desde javascript haciendo puente por la función del controlador
     */
    public function obtenerPrompt($input) {

        $prompts = [
            'cierre_diario' => "Actúa como un Administrador de Negocios Senior y Mentor Estratégico de {$input['empresa']}. Tu objetivo es revisar el cierre del día con un tono cercano, profesional y pedagógico, como si fueras un socio de confianza revisando los números para mejorar juntos.
                                Sigue estrictamente este enfoque:
                                Saludo y Balance Positivo: Inicia saludando de forma cordial. Da una visión general del día resaltando lo positivo antes de entrar en los detalles técnicos.
                                Análisis de Movimientos (El 'Día a Día'):
                                Analiza ventas y gastos con criterio de negocio, no solo contable.
                                Si hay facturas anuladas, menciónalo como un recordatorio de control interno (es normal que pase, pero mejor si está sustentado).
                                Si hay ventas a 'Consumidor Final', explica que aunque es legal y práctico, se pierde la oportunidad de fidelizar al cliente.
                                Identifica de qué caja salió más dinero y si eso afecta la operación de mañana.
                                Diagnóstico de Liquidez:
                                Revisa los saldos por medios de pago. Si hay saldos negativos, trátalos como 'alertas de nivelación' para mañana.
                                Identifica dónde está el dinero más fuerte (la reserva).
                                Consejos de Mentor (2 Recomendaciones):
                                Deben ser sugerencias estratégicas para ahorrar tiempo, dinero o mejorar la cultura del negocio.
                                Usa frases como 'Podrías intentar...', 'Una buena práctica es...', o 'Para que te desgastes menos...'.
                                IMPORTANTE: El tono debe ser ejecutivo pero empático. Evita palabras como 'inaceptable', 'error crítico' o 'prohibido'. Cambia el juicio por educación financiera.",
            
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
        // 1. Extraer metadata con validación por si Google falló (403, 400, etc)
        $usage = isset($respuestaGoogle['usageMetadata']) ? $respuestaGoogle['usageMetadata'] : array();
        $candidate = isset($respuestaGoogle['candidates'][0]) ? $respuestaGoogle['candidates'][0] : array();

        // 2. Cálculo de costos Gemini 1.5 Flash
        $p_input = 0.075 / 1000000;
        $p_output = 0.30 / 1000000;

        $tInput = isset($usage['promptTokenCount']) ? $usage['promptTokenCount'] : 0;
        $tOutput = isset($usage['candidatesTokenCount']) ? $usage['candidatesTokenCount'] : 0;
        $costo = ($tInput * $p_input) + ($tOutput * $p_output);

        // 3. Mapeo de datos para CakePHP 2
        $data = array(
            'Iaconsulta' => array( // Es buena práctica usar la llave del modelo
                'empresa_id'        => $empresaId,
                'modelo'            => isset($respuestaGoogle['modelVersion']) ? $respuestaGoogle['modelVersion'] : 'gemini-3.1-flash-lite',
                'endpoint'          => $modulo,
                'tokens_prompt'     => $tInput,
                'tokens_candidates' => $tOutput,
                'tokens_total'      => isset($usage['totalTokenCount']) ? $usage['totalTokenCount'] : 0,
                'costo_estimado_usd'=> $costo,
                'status_http'       => $statusHttp,
                'finish_reason'     => isset($candidate['finishReason']) ? $candidate['finishReason'] : 'N/A',
                'created'           => date('Y-m-d H:i:s')
            )
        );

        $this->create();
        return $this->save($data);
    }

}
