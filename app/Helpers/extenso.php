<?php

if (!function_exists('valor_por_extenso')) {
    /**
     * Transforma um valor numérico em sua representação por extenso em reais.
     *
     * @param float|int $valor
     * @return string
     */
    function valor_por_extenso($valor): string  {
        // Se for string, substitui pontos de milhar por nada e vírgula decimal por ponto
        if (is_string($valor)) {
            // Remove pontos que separam milhares e troca a vírgula por ponto
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
        }
        // Força o valor a ter apenas 2 casas decimais para evitar bugs de ponto flutuante do PHP
        $valor = round((float)$valor, 2);

        $formatador = new \NumberFormatter('pt_BR', \NumberFormatter::SPELLOUT);
        
        $inteiro = (int)$valor;
        $centavos = (int)round(($valor - $inteiro) * 100);

        $resultado = '';

        if ($inteiro > 0) {
            $textoInteiro = $formatador->format($inteiro);
            
            // Regra gramatical: valores terminados em milhões/bilhões redondos exigem o "de" (ex: um milhão DE reais)
            if ($inteiro >= 1000000 && $inteiro % 1000000 === 0) {
                $sufixo = 'de reais';
            } else {
                $sufixo = $inteiro == 1 ? 'real' : 'reais';
            }
            
            $resultado .= "{$textoInteiro} {$sufixo}";
        }

        if ($centavos > 0) {
            $textoCentavos = $formatador->format($centavos);
            $sufixoCentavos = $centavos == 1 ? 'centavo' : 'centavos';
            
            if ($inteiro > 0) {
                $resultado .= " e {$textoCentavos} {$sufixoCentavos}";
            } else {
                $resultado .= "{$textoCentavos} {$sufixoCentavos}";
            }
        }

        if (empty($resultado)) {
            $resultado = 'zero reais';
        }

        // Converte para maiúsculas e depois corrige os conectores "e" para minúsculo
        $resultadoFormatado = mb_convert_case($resultado, MB_CASE_TITLE, "UTF-8");
        
        return str_replace(' E ', ' e ', $resultadoFormatado);
    }

    
} 

if (!function_exists('forma_pagamento')) {
    /**
     * Identifica se o pagamento foi em dinheiro ou título e retorna o texto formatado.
     *
     * @param string|null $campo6
     * @return string
     */
    function identificar_forma_pagamento($campo7) {
        $valor = trim($campo7 ?? '');

        if (empty($valor)) {
            return 'NÃO INFORMADO';
        }

        // Converte para minúsculo para facilitar a busca por termos chave sem falhar por caixa alta
        $valorMinusculo = mb_strtolower($valor, 'UTF-8');

        // 1. Verifica se é Moeda Corrente / Dinheiro / Pix / Transferências Bancárias
        if (str_contains($valorMinusculo, 'dinheiro') || 
            str_contains($valorMinusculo, 'pix') || 
            str_contains($valorMinusculo, 'ted') || 
            str_contains($valorMinusculo, 'trf')) {
            return 'em moeda corrente';
        }

        // 2. Verifica se é Cheque (Ex: "Cheque nº 1234")
        if (str_contains($valorMinusculo, 'cheque')) {
            return 'através do cheque ' . $valor;
        }

        // 3. Verifica se é Nota Promissória (Movido 'np' para cá para interpretar corretamente)
        if (str_contains($valorMinusculo, 'promissoria') || 
            str_contains($valorMinusculo, 'nota') || 
            str_contains($valorMinusculo, 'np')) {
            return 'através da nota promissória ' . $valor;
        }

        // 4. Caso o campo7 tenha apenas um número ou texto genérico de outro tipo de título
        return 'através do título/documento sob nº ' . $valor;
    }
}