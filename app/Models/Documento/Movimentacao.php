<?php

namespace App\Models\Documento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movimentacao extends Model
{
    use HasFactory;

    protected $table = 'movimentos';

    public function documento()
    {
        return $this->belongsTo(Documento::class, 'documento_id');
    }
}