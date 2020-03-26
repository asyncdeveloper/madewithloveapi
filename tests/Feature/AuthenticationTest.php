<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void {
        parent::setUp();

        Artisan::call('passport:install');
    }

    /**
     * @test
     */
    public function userCanLoginWithValidEmailAndPassword()
    {
        factory(User::class, 5)->create();
        $user = factory(User::class)->create([
            'email' => 'me@example.com'
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertOk()
            ->assertJsonStructure([ 'access_token', 'expires_at' ])
            ->assertJsonMissingValidationErrors();
    }

    /**
     * @test
     */
    public function userCanNotLoginWithInvalidEmailAndPassword()
    {
        factory(User::class, 5)->create();
        $user = factory(User::class)->create([
            'email' => 'me@example.com'
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123!@#'
        ]);

        $response->assertStatus(401)
            ->assertExactJson([ "message" => "Invalid login credentials" ]);
    }
}
