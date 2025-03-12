<?php

namespace App\Http\Controllers\Api;

use App\Models\Movimentacao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MovimentacaoController extends Controller
{
    public function index()
    {
        $movimentacoes = Movimentacao::where('user_id', Auth::id())->get();
        return response()->json($movimentacoes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:entrada,saida',
            'value' => 'required|numeric',
            'category' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        $movimentacao = new Movimentacao();
        $movimentacao->user_id = Auth::id();
        $movimentacao->date = $request->date;
        $movimentacao->type = $request->type;
        $movimentacao->value = $request->value;
        $movimentacao->category = $request->category;
        $movimentacao->description = $request->description;
        $movimentacao->save();

        return response()->json(['message' => 'Movimentação criada com sucesso!'], 201);
    }

    public function destroy($id)
    {
        // Encontra a movimentação pelo ID
        $movimentacao = Movimentacao::find($id);

        if (!$movimentacao) {
            return response()->json(['message' => 'Movimentação não encontrada'], 404);
        }

        // Deleta a movimentação
        $movimentacao->delete();

        return response()->json(['message' => 'Movimentação deletada com sucesso']);
    }

    public function update(Request $request, $id)
    {
        // Encontra a movimentação pelo ID
        $movimentacao = Movimentacao::find($id);

        if (!$movimentacao) {
            return response()->json(['message' => 'Movimentação não encontrada'], 404);
        }

        // Verifica se o usuário é o proprietário da movimentação
        if ($movimentacao->user_id !== Auth::id()) {
            return response()->json(['message' => 'Acesso negado'], 403);
        }

        // Valida os dados recebidos
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:entrada,saida',
            'value' => 'required|numeric',
            'category' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        // Atualiza os dados da movimentação
        $movimentacao->date = $request->date;
        $movimentacao->type = $request->type;
        $movimentacao->value = $request->value;
        $movimentacao->category = $request->category;
        $movimentacao->description = $request->description;
        $movimentacao->save();

        return response()->json(['message' => 'Movimentação atualizada com sucesso!']);
    }
}
