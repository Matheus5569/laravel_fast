<?php

namespace App\Http\Controllers\Caminhoneiros;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class CaminhoneirosController extends Controller
{
    public function index()
    {
        return Inertia::render('caminhoneiros/Listar', [
            'caminhoneiros' => []
        ]);
    }
}