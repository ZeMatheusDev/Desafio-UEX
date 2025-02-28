<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    use HasFactory;

    protected $table = 'users_contacts';

    protected $fillable = [
        'id_user',
        'nome',
        'cpf',
        'telefone',
        'cep',
        'endereco',
        'numero',
        'bairro',
        'cidade',
        'uf',
        'complemento',
        'longitude',
        'latitude',
        'deleted',
        'status',
        'token',
    ];

    protected $casts = [
        'deleted'   => 'boolean',
        'longitude' => 'decimal:8',
        'latitude'  => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}