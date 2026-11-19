<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pessoas extends Model
{
    use HasFactory;

    protected $table = 'pessoas';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps   = false;

    // protected $fillable = [
    //     'nome',
    //     'email',
    //     'cep',
    //     'logradouro',
    //     'endereco',
    //     'numero',
    //     'nr',
    //     'bairro',
    //     'complemento',
    //     'dt_nascimento',
    //     'cpf_cnpj',
    //     'rg_ie',
    //     'pis',
    //     'associado',
    //     'associado_id',
    //     'pessoa_tp',
    //     'esta_ibge',
    //     'muni_ibge',
    //     'obs',
    //     'status',
    //     'avatar',
    //     'nivel',
    //     'last_used_at',
    //     'endereco_id'
    // ];
    protected $fillable = [
        'nome','email','cpf_cnpj','rg_ie','pis','status','pessoa_tp','obs',
        'esta_ibge','muni_ibge','logradouro','endereco','bairro','nr','cep',
        'complemento','dt_nascimento','last_used_at','avatar',
        'associado','associado_id','endereco_id','nivel','telefone',
    ];

    protected $casts = [
        'associado'     => 'boolean',
        'status'        => 'integer',
        'last_used_at'  => 'datetime:Y-m-d H:i:s',
    ];

    /* =========================================================================
     | AVATAR
     ========================================================================= */

    /** Retorna a URL completa da imagem do avatar */
    public function getAvatarImageAttribute()
    {
        return $this->avatar
            ? asset($this->avatar)
            : asset('images/null.png');
    }

    /** Retorna somente o nome do arquivo do avatar */
    public function getAvatarFilenameAttribute()
    {
        return $this->avatar ? basename($this->avatar) : null;
    }

    /**
     * Mutator do avatar.
     * Controller deve mover o arquivo físico. Aqui só define o caminho.
     * Assim:
     * Se você mandar storage/avatars/2025/.../arquivo.png → não mexe
     * Se mandar só arquivo.png → gera nome automático 
    */
    // public function setAvatarAttribute($value){
    //     if (!$value) {
    //         $this->attributes['avatar'] = null;
    //         return;
    //     }

    //     // se já vier um caminho completo, NÃO alterar
    //     if (str_contains($value, 'storage/avatars')) {
    //         $this->attributes['avatar'] = $value;
    //         return;
    //     }

    //     // mantém regra antiga quando for apenas o nome
    //     $filename = substr(md5(mt_rand()), 0, 7) . '_' . basename($value);
    //     $folder = 'storage/avatars/' . date('Y/m/d') . '/';

    //     $this->attributes['avatar'] = $folder . $filename;
    // }

    /* =========================================================================
     | DATA DE NASCIMENTO (ACCESSOR / MUTATOR)
     ========================================================================= */

    /** Converte yyyy-mm-dd → dd/mm/yyyy */
   public function setDtNascimentoAttribute($value){
        if (empty($value)) {
            $this->attributes['dt_nascimento'] = null;
            return;
        }

        try {
            // Se vier yyyy-mm-dd (HTML date)
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                $this->attributes['dt_nascimento'] =
                    Carbon::parse($value)->format('Y-m-d');
                return;
            }

            // Se vier dd/mm/yyyy (manual)
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
                $this->attributes['dt_nascimento'] =
                    Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
                return;
            }

            // Qualquer outra coisa → salva null
            $this->attributes['dt_nascimento'] = null;

        } catch (\Exception $e) {
            $this->attributes['dt_nascimento'] = null;
        }
    }
    /* =========================================================================
     | RELACIONAMENTOS
     ========================================================================= */

    public function compromissos()
    {
        return $this->hasMany(\App\Models\Compromisso::class, 'pessoa_id');
    }
}