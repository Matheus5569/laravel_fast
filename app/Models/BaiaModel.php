<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BaiaModel extends BaseModel
{
    protected $table = 'tb_baia';

    protected $fillable = [
        'nome',
        'descricao',
        'status',
        'id_admin',
        'removido',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'id_admin',
            'id'
        );
    }
}