<?php

namespace App\Http\Controllers\Baias;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class BaiasController extends Controller
{
    public function index()
    {
        return Inertia::render('baias/Listar', [
            'baias' => []
        ]);
    }
}