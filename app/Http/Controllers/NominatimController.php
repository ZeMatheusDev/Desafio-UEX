<?php

namespace App\Http\Controllers;

use App\Services\NominatimServiceInterface;
use Illuminate\Http\Request;

class NominatimController extends Controller
{
    protected $nominatimService;

    public function __construct(NominatimServiceInterface $nominatimService)
    {
        $this->nominatimService = $nominatimService;
    }
    
    public function searchGeo(Request $request)
    {
        try {
            $cep = $request->input('cep');

            if (empty($cep)) {
                return response()->json(['error' => 'CEP é obrigatório'], 400);
            }
            
            $geoLoc = $this->nominatimService->searchGeo($cep);
            return response()->json($geoLoc);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
