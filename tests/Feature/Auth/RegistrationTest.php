<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Contracts\SchoolDataProvider;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $this->instance(
            SchoolDataProvider::class,
            \Mockery::mock(SchoolDataProvider::class, function (MockInterface $mock) {
                $mock->shouldReceive('getEmployee')->andReturn(['some' => 'data']);
            })
        );

        $response = $this->post('/register', [
            'provider_id' => 'A123456',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
