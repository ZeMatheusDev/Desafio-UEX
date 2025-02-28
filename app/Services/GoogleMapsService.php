<?php
namespace App\Services;

use App\Models\Contacts;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleMapsService implements GoogleMapsServiceInterface
{
    protected $options;

    public function __construct()
    {
        $this->options = [
            'verify' => storage_path('certs/cacert.pem')
        ];
    }

    public function getContactMap($id)
    {
        $contact = Contacts::find($id);
        if (!$contact || !$contact->latitude || !$contact->longitude) {
            return response()->json(['error' => 'Contato não encontrado ou sem localização'], 404);
        }

        $lat = $contact->latitude;
        $lng = $contact->longitude;
        $apiKey = config('services.google_maps.key');
        $mapUrl = "https://maps.googleapis.com/maps/api/staticmap?center={$lat},{$lng}&zoom=15&size=600x300&markers=color:red%7Clabel:C%7C{$lat},{$lng}&key={$apiKey}";
        return response()->json(['map_url' => $mapUrl]);
    }
}