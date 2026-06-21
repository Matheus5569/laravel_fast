<?php

namespace App\Models;

class CaminhoneiroModel extends BaseModel
{
    protected $table = 'tb_caminhoneiro';

    protected $fillable = [

        'nome',
        'cpf',
        'telefone',
        'placa_caminhao',
        'status',
        'id_admin',
        'removido',
    ];
}