<?php
namespace App\Services;

interface NominatimServiceInterface
{
    public function searchGeo(string $cep);
}