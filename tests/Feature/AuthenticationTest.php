<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
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

    /**
     * @test
     */
    public function userCanLogOutWithAccessToken()
    {
        $user = factory(User::class)->make();

        Passport::actingAs($user);

        $response = $this->get(route('logout'));

        $response->assertOk()
            ->assertExactJson([ "message" => "Logged out successfully" ]);
    }
}
