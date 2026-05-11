<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendedorModel extends BaseModel
{
        //colocamos o fillable para dizer todos os campos que vamos editar ou cadastrar

    protected $table = 'tb_vendedor';

    protected $primaryKey = 'id_vendedor';

    protected $fillable = [
        'id_vendedor',
        'comissao',
        'cpf',
        'observacoes',
        'id_endereco',
        'id_admin',
        'removido'

    ];

    public function endereco(): BelongsTo
    {
        return $this->belongsTo(EnderecoModel::class, 'id_endereco', 'id');
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_vendedor', 'id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_admin', 'id');
    }



}
