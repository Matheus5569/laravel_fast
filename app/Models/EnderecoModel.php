<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnderecoModel extends Model
{
        //colocamos o fillable para dizer todos os campos que vamos editar ou cadastrar
protected $table = 'tb_endereco';

    protected $fillable = [
        'cep',
        'rua',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'removido',
    ];
}
