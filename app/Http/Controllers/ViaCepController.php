<?php

namespace App\Http\Controllers;

use App\Services\ViaCepServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ViaCepController extends Controller
{
    protected $viaCepService;

    public function __construct(ViaCepServiceInterface $viaCepService)
    {
        $this->viaCepService = $viaCepService;
    }

    public function searchEndereco(Request $request)
    {
        try {
            $enderecos = $this->viaCepService->searchEndereco(
                $request->input('uf'),
                $request->input('cidade'),
                $request->input('logradouro')
            );
            return response()->json($enderecos);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function searchCep(Request $request)
    {
        try {

            $enderecos = $this->viaCepService->searchCep(
                $request->input('cep')
            );
            return response()->json($enderecos);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
