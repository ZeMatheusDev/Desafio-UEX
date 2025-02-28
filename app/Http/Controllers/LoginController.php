<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'senha' => 'required'
        ], [
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'senha.required' => 'A senha é obrigatória.'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Verifique os erros no formulário'
            ], 422);
        }
        
        $user = \App\Models\User::where('email', $request->email)->where('deleted', 0)->first();
    
        if (!$user || !Hash::check($request->senha, $user->senha)) {
            return response()->json([
                'errors' => ['error' => ['Conta não encontrada']],
                'message' => 'Verifique os erros no formulário'
            ], 422);
        }
    
        Auth::login($user);
    
        $request->session()->regenerate();

        $request->session()->put([
            'email' => $user->email,
            'nome' => $user->nome 
        ]);

        return redirect()->back();
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken(); 
    
        return redirect()->route('home'); 
    }
}
