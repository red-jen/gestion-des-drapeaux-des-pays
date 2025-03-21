<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Country;

class CountryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_get_countries_list()
    {
        // Login with the user
        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        // Create some test countries
        Country::factory()->count(3)->create();

        // Test the API endpoint with the authenticated user
        $response = $this->actingAs($this->user)->getJson('/api/countries');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data' => [
                         '*' => [
                             'id',
                             'name',
                             'capital',
                             'population',
                             'region',
                             'created_at',
                             'updated_at'
                         ]
                     ]
                 ]);
    }

    public function test_user_can_create_country()
    {
        $countryData = [
            'name' => 'Test Country',
            'capital' => 'Test Capital',
            'population' => 1000000,
            'region' => 'Test Region',
            'currency' => 'Test Currency',
            'language' => 'Test Language'
        ];

        $response = $this->actingAs($this->user)
                         ->postJson('/api/countries', $countryData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'name' => 'Test Country',
                     'capital' => 'Test Capital'
                 ]);
    }

    public function test_user_can_update_country()
    {
        $country = Country::factory()->create();

        $updatedData = [
            'name' => 'Updated Country',
            'population' => 2000000
        ];

        $response = $this->actingAs($this->user)
                         ->putJson("/api/countries/{$country->id}", $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name' => 'Updated Country',
                     'population' => 2000000
                 ]);
    }

    public function test_user_can_delete_country()
    {
        $country = Country::factory()->create();

        $response = $this->actingAs($this->user)
                         ->deleteJson("/api/countries/{$country->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('countries', ['id' => $country->id]);
    }

    public function test_guest_cannot_access_protected_routes()
    {
        $response = $this->getJson('/api/countries');
        $response->assertStatus(401);
    }
}