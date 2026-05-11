<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdutoModel extends BaseModel
{
    //colocamos o fillable para dizer todos os campos que vamos editar ou cadastrar

    protected $table = 'tb_produto';
    protected $fillable = [

        'nome',
        'preco',
        'id_admin',
        'removido',
    ];
        public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_admin', 'id');
    }
}
