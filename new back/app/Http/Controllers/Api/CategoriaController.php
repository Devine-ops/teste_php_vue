<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index()
    {
        return response()->json(Categoria::all());
    }

    public function store(Request $request)
    {
        $request->validate(['nome' => 'required|unique:categorias|max:255']);

        $categoria = Categoria::create(['nome' => $request->nome]);

        return response()->json(['message' => 'Categoria criada!', 'categoria' => $categoria]);
    }
}
