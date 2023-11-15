<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Contracts\SchoolDataProvider;
use App\Models\User;
use Mockery\MockInterface;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function test_it_shows_a_dashboard(): void
    {
        $user = User::factory()->create();

        $viewData = [
            ['id' => fake()->word, 'name' => fake()->name],
            ['id' => fake()->word, 'name' => fake()->name],
        ];

        $this->instance(
            SchoolDataProvider::class,
            \Mockery::mock(
                SchoolDataProvider::class,
                fn (MockInterface $mock) => $mock->shouldReceive('getEmployeeClasses')
                    ->with($user->provider_id)
                    ->andReturn($viewData))
        );

        $response = $this
            ->actingAs($user)
            ->get('/dashboard');

        $response->assertOk()->assertViewHas('classes', $viewData);
    }
}
