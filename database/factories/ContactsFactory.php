<?php

namespace Database\Factories;

use App\Models\Contacts;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactsFactory extends Factory
{
    protected $model = Contacts::class;

    public function definition()
    {
        return [
            'id_user' => User::factory(), 
            'nome' => $this->faker->name(),
            'cpf' => $this->faker->randomNumber(9, true) . $this->faker->randomNumber(2, true),
            'cep' => $this->faker->randomNumber(9, true) . $this->faker->randomNumber(2, true),
            'endereco' => $this->faker->randomNumber(9, true) . $this->faker->randomNumber(2, true),
            'numero' => $this->faker->randomNumber(9, true) . $this->faker->randomNumber(2, true),
            'bairro' => $this->faker->randomNumber(9, true) . $this->faker->randomNumber(2, true),
            'cidade' => $this->faker->randomNumber(9, true) . $this->faker->randomNumber(2, true),
            'uf' => 'AL',
            'complemento' => $this->faker->randomNumber(9, true) . $this->faker->randomNumber(2, true),
            'telefone' => $this->faker->phoneNumber(),
            'deleted' => 0, 
        ];
    }
}
