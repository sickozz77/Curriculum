<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCompromisso
 */
class Compromisso extends Model
{
    protected $fillable = [ 'pessoa_id', 
                            'titulo', 
                            'data_hora'
                          ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoas::class);
    }
}
