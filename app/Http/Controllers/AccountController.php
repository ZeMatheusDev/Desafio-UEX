<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public function forgotPasswordPage(){
        return Inertia::render('ForgotPassword');
    }

    public function createAccountPage(){
        return Inertia::render('CreateAccount');
    }

    public function createAccountStore(Request $request)
    {
        $request->merge([
            'cpf' => preg_replace('/[^0-9]/', '', $request->cpf)
        ]);
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->where('deleted', 0)
            ],
            'senha' => [
                'required',
                'max:255'
            ],
            'cpf' => [
                'required',
                Rule::unique('users', 'cpf')->where('deleted', 0)
            ],
            'nome' => 'required|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ], [
            'cpf.unique' => 'Este CPF já está cadastrado',
            'email.unique' => 'Este e-mail já está cadastrado',
            'cpf.required' => 'O campo CPF é obrigatório',
            'nome.required' => 'O campo Nome é obrigatório',
            'email.required' => 'o campo E-mail é obrigatório',
            'senha.required' => 'O campo Senha é obrigatório',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Verifique os erros no formulário'
            ], 422);
        }
        
        $token = md5(date("Y-m-d H:i:s") . rand(0, 999999999));

        $senhaCriptografada = Hash::make($request['senha']);

        User::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => $senhaCriptografada,
            'cpf' => preg_replace('/[^0-9]/', '', $request->cpf),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 0,
            'deleted' => 0,
            'token' => $token,
        ]);
    }

    public function forgotPasswordStore(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ], [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O e-mail informado não é válido.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Verifique os erros no formulário.'
            ], 422);
        }

        $user = User::where('email', $request->email)
                ->where('deleted', 0)
                ->first();

        if (!$user) {
            return response()->json([
                'errors' => ['email' => ['O e-mail informado não está cadastrado.']],
                'message' => 'Verifique os erros no formulário.'
            ], 422);
        }

        $novaSenha = Str::random(8); 
        $user->senha = Hash::make($novaSenha);
        $user->save();

        Mail::to($user->email)->send(new ForgotPassword($novaSenha));

        return response()->json([
            'message' => 'Um email com instruções foi enviado para sua caixa postal.'
        ]);
    }

    public function editAccount(){
        return Inertia::render('EditAccount');
    }

    public function deleteAccount(){
        User::where('id', auth()->id())->update(['deleted' => 1]);
        auth()->logout();
    
        return redirect()->route('home'); 
    }

    public function changePassword(Request $request){
        $senhaCriptografada = Hash::make($request->senha);

        User::where('id', auth()->id())->update(['senha' => $senhaCriptografada]);
        auth()->logout();
    
        return redirect()->route('home'); 
    }
}
