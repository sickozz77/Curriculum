<?php

namespace App\Traits;

trait HydratesCompatRelations
{
    private function hydrateCompatRelations($rows)
    {
        $rows = collect($rows);
        return $rows->map(function ($r) {
            $r->requerente = (object) ['nome' => $r->requerente_nome ?? null];
            $r->requerido  = (object) ['nome' => $r->requerido_nome  ?? null];
            $r->texto      = (object) ['nome' => $r->texto_nm        ?? null];
            $r->advogado   = (object) ['nome' => $r->advogado_nm     ?? null];
            return $r;
        });
    }
}