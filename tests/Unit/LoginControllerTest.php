<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_validation_error()
    {
        $response = $this->postJson(route('login'), [
            'email' => 'email-invalido',
            'senha' => ''
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors' => ['email', 'senha'],
            'message'
        ]);
    }

    public function test_login_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'teste@exemplo.com',
            'senha' => Hash::make('senha_correta'),
            'deleted' => 0,
        ]);

        $response = $this->postJson(route('login'), [
            'email' => 'teste@exemplo.com',
            'senha' => 'senha_errada'
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('errors.error.0', 'Conta não encontrada');
    }

    public function test_login_success()
    {
        $user = User::factory()->create([
            'email' => 'teste@exemplo.com',
            'senha' => Hash::make('senha_correta'),
            'deleted' => 0,
            'nome' => 'Nome de Teste'
        ]);

        $response = $this->post(route('login'), [
            'email' => 'teste@exemplo.com',
            'senha' => 'senha_correta'
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);
        $this->assertEquals('teste@exemplo.com', session('email'));
        $this->assertEquals('Nome de Teste', session('nome'));
    }

    public function test_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }
}
