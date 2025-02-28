<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\DashboardController;
use App\Models\Contacts;
use App\Models\User;
use App\Services\GoogleMapsServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_retorna_inertia_com_dados_corretos()
    {
        $user = User::factory()->create(); 
    
        $contact = Contacts::factory()->create([
            'id_user' => $user->id, 
        ]);
    
        $response = $this->actingAs($user)->get(route('dashboard'));
    
        $response->assertInertia(fn ($page) => $page
            ->component('Dashboard')
            ->has('contatos', 1) 
        );
    }
}
