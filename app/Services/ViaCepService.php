<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class ViaCepService implements ViaCepServiceInterface
{
    protected $options;

    public function __construct()
    {
        $this->options = [
            'verify' => storage_path('certs/cacert.pem')
        ];
    }

    public function searchEndereco(string $uf, string $cidade, string $logradouro): array
    {
        $url = "https://viacep.com.br/ws/{$uf}/{$cidade}/" . urlencode($logradouro) . "/json/";
        $response = Http::withOptions($this->options)->get($url);
        
        if ($response->successful()) {
            return $response->json();
        }
        
        throw new \Exception('Erro na consulta ao ViaCep');
    }

    public function searchCep(string $cep): array
    {
        $url = "https://viacep.com.br/ws/{$cep}/json/";
        $response = Http::withOptions($this->options)->get($url);
        
        if ($response->successful()) {
            return $response->json();
        }
        
        throw new \Exception('Erro na consulta ao ViaCep');
    }
}