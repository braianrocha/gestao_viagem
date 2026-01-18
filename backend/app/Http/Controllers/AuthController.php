<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Login da API
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            // Verifica se o usuário enviou um  email inválido
            if ($errors->has('email')) {
                return response()->json([
                    'error' => 'O formato do e-mail está incorreto ou é inválido.',
                    'code'  => 'EMAIL_INVALIDO' 
                ], 422);
            }

            // Retorno para outros erros  
            return response()->json([
                'error' => 'Dados inválidos!',
                'details' => $errors->all()
            ], 422);
        }

        // Se as credenciais válidas, tenta autenticar
        $credentials = $validator->validated();
        if (!$token = Auth::attempt($credentials)) {
            // Credenciais inválidas
            return response()->json([
                'error' => 'Usuário ou senha incorretos.',
                'code'  => 'CREDENCIAIS_INVALIDAS'
            ], 401);
        }

        // Autenticação bem-sucedida, retorna o token
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60
        ]);
    }

    // Info do usuário autenticado
    public function me()
    {
        // Retorna os dados do usuário autenticado
        return response()->json(auth()->user());
    }

    // Logout da API
    public function logout()
    {
        // Invalida o token do usuário autenticado
        auth()->logout();

        return response()->json([
            'message' => 'Logout realizado com sucesso!'
        ]);
    }
}
