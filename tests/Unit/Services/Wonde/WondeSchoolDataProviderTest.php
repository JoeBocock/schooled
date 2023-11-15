<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Wonde;

use App\Contracts\SchoolDataProvider;
use App\Services\Wonde\WondeSchoolDataProvider;
use Mockery\MockInterface;
use Tests\TestCase;
use Wonde\Client;

class WondeSchoolDataProviderTest extends TestCase
{
    public function test_that_it_gets_resolved(): void
    {
        $this->assertInstanceOf(WondeSchoolDataProvider::class, $this->app->make(SchoolDataProvider::class));
    }

    public function test_that_it_gets_an_employee(): void
    {
        $employeeId = fake()->word;
        $employee = ['id' => fake()->word];

        $this->instance(
            Client::class,
            \Mockery::mock(Client::class, function (MockInterface $mock) use ($employeeId, $employee) {
                $mock->shouldReceive('school')
                    ->with(config('services.wonde.school'))
                    ->andReturn($mock);

                $mock->employees = $mock;

                $mock->shouldReceive('get')
                    ->with($employeeId)
                    ->andReturn((object) $employee);
            })
        );

        $provider = $this->app->make(SchoolDataProvider::class);

        $this->assertEquals($employee, $provider->getEmployee($employeeId));
    }

    public function test_that_it_gets_employee_classes(): void
    {
        $employeeId = fake()->word;
        $employee = ['classes' => [
            'data' => [
                'id' => fake()->word,
            ],
        ]];

        $this->instance(
            Client::class,
            \Mockery::mock(Client::class, function (MockInterface $mock) use ($employeeId, $employee) {
                $mock->shouldReceive('school')
                    ->with(config('services.wonde.school'))
                    ->andReturn($mock);

                $mock->employees = $mock;

                $mock->shouldReceive('get')
                    ->with($employeeId, ['classes'])
                    ->andReturn((object) $employee);
            })
        );

        $provider = $this->app->make(SchoolDataProvider::class);

        $this->assertEquals($employee['classes']['data'], $provider->getEmployeeClasses($employeeId));
    }

    public function test_that_it_gets_a_class_with_students_for_employee(): void
    {
        $employeeId = fake()->word;
        $classId = fake()->word;

        $employee = ['classes' => [
            'data' => [
                ['id' => $classId],
                ['id' => 'some-other-class'],
            ],
        ]];

        $class = ['id' => $classId, 'students' => [['id' => fake()->word]]];

        $this->instance(
            Client::class,
            \Mockery::mock(Client::class, function (MockInterface $mock) use ($employeeId, $employee, $classId, $class) {
                $mock->shouldReceive('school')
                    ->with(config('services.wonde.school'))
                    ->andReturn($mock);

                $mock->employees = $mock;

                $mock->shouldReceive('get')
                    ->with($employeeId, ['classes'])
                    ->andReturn((object) $employee);

                $mock->shouldReceive('school')
                    ->with(config('services.wonde.school'))
                    ->andReturn($mock);

                $mock->classes = $mock;

                $mock->shouldReceive('get')
                    ->with($classId, ['students'])
                    ->andReturn((object) $class);
            })
        );

        $provider = $this->app->make(SchoolDataProvider::class);

        $this->assertEquals($class, $provider->getClassWithStudents($classId, $employeeId));
    }
}
