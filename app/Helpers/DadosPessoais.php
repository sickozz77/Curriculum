<?php

namespace App\Helpers;

class DadosPessoais
{
    public static function maskCpfCnpj($doc)
    {
        $doc = preg_replace('/\D/', '', $doc);

        if (strlen($doc) === 11) {  // CPF
            return substr($doc, 0, 3) . '.***.***-' . substr($doc, -2);
        } elseif (strlen($doc) === 14) {  // CNPJ
            return substr($doc, 0, 2) . '.***.***/****-' . substr($doc, -2);
        } else {
            return 'Documento inválido';
        }
    }
    
    public static function maskRg($rg)
    {
        $rg = preg_replace('/\D/', '', (string) $rg);
        $len = strlen($rg);

        // RG pode variar por estado: aceite 8 a 10 dígitos
        if ($len < 8 || $len > 10) {
            return 'Documento inválido';
        }

        $dv   = substr($rg, -1);                      // dígito verificador
        $lead = ($len === 8) ? substr($rg, 0, 1)      // em RG de 8 dígitos mostra 1 na frente
                            : substr($rg, 0, 2);     // senão, mostra 2

        return $lead . '.***.***-' . $dv;
    }

    public static function maskNis($nis)
    {
        $nis = preg_replace('/\D/', '', $nis);

        if (strlen($nis) !== 11) {
            return 'NIS inválido';
        }

        return substr($nis, 0, 3) . '.***.***-' . substr($nis, -2);
    }

}
