<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItensModel extends BaseModel
{
    //colocamos o fillable para dizer todos os campos que vamos editar ou cadastrar
    protected $table = 'tb_itens';

    protected $fillable = [
        'id_venda',
        'id_produto',
        'quantidade', 
        'valor',
        'id_admin',
        'removido',
    ];
    public function venda(): BelongsTo
    {
        return $this->belongsTo(VendaModel::class, 'id_venda', 'id');
    }
    public function produto(): BelongsTo
    {
        return $this->belongsTo(ProdutoModel::class, 'id_produto', 'id');
    }
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_admin', 'id');
    }
}
