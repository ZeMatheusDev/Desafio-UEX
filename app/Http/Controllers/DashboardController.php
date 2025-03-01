<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use App\Services\GoogleMapsService;
use App\Services\GoogleMapsServiceInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(GoogleMapsServiceInterface $googleMaps){
        $contatos = Contacts::where('deleted', 0)->where('id_user', auth()->id())->get()->toArray();
        
        return Inertia::render('Dashboard', [
            'contatos' => $contatos,
        ]);
    }

    public function getContactMap($id, GoogleMapsServiceInterface $googleMaps)
    {
        $maps = $googleMaps->getContactMap($id);
        return response()->json($maps);
    }
}
