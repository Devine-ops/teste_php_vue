<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Exception;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {   //validar email e senha
        if(Auth::attempt(['email' => $request->email, 'password'=> $request->password])){
            //recupera dados do user
            $user = Auth::user();

            $token = $request->user()->createToken('api-token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Logado!',
                'user' => $user,
                'token' => $token
            ],201);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Login ou senha incorreta!'
            ],404);
        }
    }
 
    public function logout(User $user)
    {
        try{

            $user->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Deslogado com sucesso.',
            ], 200);

        } catch (Exception $e){

            return response()->json([
                'status' => false,
                'message' => 'Não deslogado.',
            ], 400);

        }
    }
}
