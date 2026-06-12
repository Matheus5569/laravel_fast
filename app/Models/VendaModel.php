<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\RastreamentoModel;

class VendaModel extends BaseModel
{
    //colocamos o fillable para dizer todos os campos que vamos editar ou cadastrar
    protected $table = 'tb_venda';
    protected $fillable = [

        'data',
        'id_cliente',
        'id_vendedor',
        'id_admin',
        'removido',
        'qr_code',
        'status_atual',
        'baia',
        'caminhoneiro',
        'destino',

    ];
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(ClienteModel::class, 'id_cliente');
    }
    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(VendedorModel::class, 'id_vendedor');
    }
    public function itens()
    {
        return $this->hasMany(ItensModel::class, 'id_venda');
    }
    public function rastreamento(): HasMany
    {
        return $this->hasMany(RastreamentoModel::class, 'id_venda');
    }



}
