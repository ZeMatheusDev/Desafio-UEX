<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use App\Services\NominatimServiceInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    public function create(){
        return Inertia::render('Contacts/Create');
    }

    public function store(Request $request, NominatimServiceInterface $nominatimService)
    {
        $request->merge([
            'cpf' => preg_replace('/[^0-9]/', '', $request->cpf)
        ]);

        $validator = Validator::make($request->all(), [
            'nome' => [
                'required',
                'string',
                'max:255'
            ],
            'cpf' => [
                'required',
                'string',
                'size:11',
                Rule::unique('users_contacts', 'cpf')->where('deleted', 0)
            ],
            'telefone' => [
                'required',
                'string',
                'max:15'
            ],
            'cep' => [
                'required',
                'string',
                'max:9'
            ],
            'endereco' => [
                'required',
                'string',
                'max:255'
            ],
            'numero' => [
                'required',
                'string',
                'max:20'
            ],
            'bairro' => [
                'required',
                'string',
                'max:255'
            ],
            'cidade' => [
                'required',
                'string',
                'max:255'
            ],
            'uf' => [
                'required',
                'string',
                'size:2'
            ],
        ], [
            'nome.required' => 'O campo Nome é obrigatório',
            'cpf.required' => 'O campo CPF é obrigatório',
            'cpf.size' => 'CPF deve conter 11 dígitos',
            'cpf.unique' => 'Este CPF já está cadastrado',
            'telefone.required' => 'O campo Telefone é obrigatório',
            'cep.required' => 'O campo CEP é obrigatório',
            'endereco.required' => 'O campo Endereço é obrigatório',
            'numero.required' => 'O campo Número é obrigatório',
            'bairro.required' => 'O campo Bairro é obrigatório',
            'cidade.required' => 'O campo Cidade é obrigatório',
            'uf.required' => 'O campo UF é obrigatório',
            'uf.size' => 'UF deve conter exatamente 2 caracteres',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $token = md5(date("Y-m-d H:i:s") . rand(0, 999999999));

        $cep = preg_replace('/[^0-9]/', '', $request->cep);
        $geoLoc = $nominatimService->searchGeo($cep);
        $contact = Contacts::create([
            'id_user' => auth()->id(),
            'nome' => $request->nome,
            'cpf' => $request->cpf,
            'telefone' => $request->telefone,
            'cep' => preg_replace('/[^0-9]/', '', $request->cep),
            'endereco' => $request->endereco,
            'numero' => $request->numero,
            'latitude' => $geoLoc[0]['lat'],
            'longitude' => $geoLoc[0]['lon'],
            'bairro' => $request->bairro,
            'cidade' => $request->cidade,
            'uf' => $request->uf,
            'complemento' => $request->complemento,
            'status' => 0,
            'deleted' => 0,
            'token' => $token,
        ]);

        return response()->json([
            'success' => true,
            'data' => $contact,
            'message' => 'Contato cadastrado com sucesso!'
        ], 200);
    }

    public function edit($token){
        $contato = Contacts::where('token', $token)->first();

        return Inertia::render('Contacts/Edit', [
            'contato' => $contato,
        ]);
    }

    public function update(Request $request, NominatimServiceInterface $nominatimService)
    {
        $request->merge([
            'cpf' => preg_replace('/[^0-9]/', '', $request->cpf)
        ]);

        $contactId = $request->id;

        $validator = Validator::make($request->all(), [
            'nome' => [
                'required',
                'string',
                'max:255'
            ],
            'cpf' => [
                'required',
                'string',
                'size:11',
                Rule::unique('users_contacts', 'cpf')->where('deleted', 0)->ignore($contactId, 'id')
            ],
            'telefone' => [
                'required',
                'string',
                'max:15'
            ],
            'cep' => [
                'required',
                'string',
                'max:9'
            ],
            'endereco' => [
                'required',
                'string',
                'max:255'
            ],
            'numero' => [
                'required',
                'string',
                'max:20'
            ],
            'bairro' => [
                'required',
                'string',
                'max:255'
            ],
            'cidade' => [
                'required',
                'string',
                'max:255'
            ],
            'uf' => [
                'required',
                'string',
                'size:2'
            ],
        ], [
            'nome.required' => 'O campo Nome é obrigatório',
            'cpf.required' => 'O campo CPF é obrigatório',
            'cpf.size' => 'CPF deve conter 11 dígitos',
            'cpf.unique' => 'Este CPF já está cadastrado',
            'telefone.required' => 'O campo Telefone é obrigatório',
            'cep.required' => 'O campo CEP é obrigatório',
            'endereco.required' => 'O campo Endereço é obrigatório',
            'numero.required' => 'O campo Número é obrigatório',
            'bairro.required' => 'O campo Bairro é obrigatório',
            'cidade.required' => 'O campo Cidade é obrigatório',
            'uf.required' => 'O campo UF é obrigatório',
            'uf.size' => 'UF deve conter exatamente 2 caracteres',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        $contact = Contacts::findOrFail($contactId);

        $cep = preg_replace('/[^0-9]/', '', $request->cep);
        $geoLoc = $nominatimService->searchGeo($cep);
        $contact->update([
            'nome' => $request->nome,
            'cpf' => $request->cpf,
            'telefone' => $request->telefone,
            'cep' => preg_replace('/[^0-9]/', '', $request->cep),
            'endereco' => $request->endereco,
            'numero' => $request->numero,
            'bairro' => $request->bairro,
            'cidade' => $request->cidade,
            'uf' => $request->uf,
            'complemento' => $request->complemento,
            'latitude' => $geoLoc[0]['lat'] ?? $contact->latitude,
            'longitude' => $geoLoc[0]['lon'] ?? $contact->longitude,
        ]);

        return response()->json([
            'success' => true,
            'data' => $contact,
            'message' => 'Contato cadastrado com sucesso!'
        ], 200);
    }

    public function delete(Request $request){
        $contact = Contacts::findOrFail($request->id);
        $contact->update([
            'deleted' => 1,
        ]);
    }
}
