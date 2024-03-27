<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class MantenimientoController extends Controller
{
    //
    public function usuarios()
    {
        return Inertia::render('mantenimiento/Usuarios');
    }
    public function departamentos()
    {
        return Inertia::render('mantenimiento/Departamentos');
    }
}
