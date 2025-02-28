<?php
namespace App\Services;

interface ViaCepServiceInterface
{
    public function searchEndereco(string $uf, string $cidade, string $logradouro): array;
    public function searchCep(string $cep): array;
}