<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NominatimService implements NominatimServiceInterface
{
    protected $options;

    public function __construct()
    {
        $this->options = [
            'verify' => storage_path('certs/cacert.pem')
        ];
    }

    public function searchGeo(string $cep): array
    {
        $url = "https://nominatim.openstreetmap.org/search";
        $response = Http::withHeaders([
                'User-Agent' => 'MeuApp/1.0 (meuemail@dominio.com)'
            ])
            ->withOptions($this->options) 
            ->get($url, [
                'country'    => 'Brazil',
                'format'     => 'json',
                'postalcode' => $cep,
            ]);
    
        if ($response->successful()) {
            return $response->json();
        }
        
        Log::error("Erro ao buscar geo para o CEP {$cep}: " . $response->body());
        
        return [];
    }
}