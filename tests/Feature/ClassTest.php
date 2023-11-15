<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Contracts\SchoolDataProvider;
use App\Models\User;
use Mockery\MockInterface;
use Tests\TestCase;

class ClassTest extends TestCase
{
    public function test_it_shows_a_class(): void
    {
        $user = User::factory()->create();
        $classId = fake()->word;

        $viewData = [
            'name' => fake()->name,
            'students' => [
                'data' => [
                    ['id' => fake()->word, 'surname' => fake()->name, 'forename' => fake()->name],
                    ['id' => fake()->word, 'surname' => fake()->name, 'forename' => fake()->name],
                ],
            ],
        ];

        $this->instance(
            SchoolDataProvider::class,
            \Mockery::mock(
                SchoolDataProvider::class,
                fn (MockInterface $mock) => $mock->shouldReceive('getClassWithStudents')
                    ->with($classId, $user->provider_id)
                    ->andReturn($viewData))
        );

        $response = $this
            ->actingAs($user)
            ->get("/classes/{$classId}");

        $response->assertOk()->assertViewHas('class', $viewData);
    }

    public function test_it_can_be_not_found(): void
    {
        $user = User::factory()->create();
        $classId = fake()->word;

        $this->instance(
            SchoolDataProvider::class,
            \Mockery::mock(
                SchoolDataProvider::class,
                fn (MockInterface $mock) => $mock->shouldReceive('getClassWithStudents')
                    ->with($classId, $user->provider_id)
                    ->andReturn([]))
        );

        $response = $this
            ->actingAs($user)
            ->get("/classes/{$classId}");

        $response->assertNotFound();
    }
}
