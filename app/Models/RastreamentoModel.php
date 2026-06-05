<?php

namespace App\Models;

class RastreamentoModel extends BaseModel
{
    protected $table = 'tb_rastreamento';

    protected $fillable = [
        'id_venda',
        'status',
        'observacao'
    ];

    public function venda()
    {
        return $this->belongsTo(VendaModel::class, 'id_venda');
    }
}