<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClienteModel extends BaseModel
{
    //colocamos o fillable para dizer todos os campos que vamos editar ou cadastrar

    protected $table = 'tb_cliente';
    protected $primaryKey = 'id';
    protected $fillable = [

        'nome',
        'email',
        'id_endereco',
        'id_admin',
        'removido',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_admin', 'id');
    }
    public function endereco(): BelongsTo
    {
        return $this->belongsTo(EnderecoModel::class, 'id_endereco', 'id');
    }
    //o id_endereco e relacionado e essa tabela do cliente
    //e o id e relacionado a propria tabela endereco que tem por padrão do laravel o id 

}
