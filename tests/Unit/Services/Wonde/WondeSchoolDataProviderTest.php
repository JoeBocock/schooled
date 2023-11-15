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
        $employeeId = 'abc';
        $employee = ['id' => $employeeId];

        $this->instance(
            Client::class,
            \Mockery::mock(Client::class, function (MockInterface $mock) use ($employeeId, $employee) {
                $mock->shouldReceive('school')
                    ->with(config('services.wonde.school'))
                    ->andReturn($mock);

                $mock->employees = $mock;

                $mock->shouldReceive('get')
                    ->with($employeeId)
                    ->andReturn($employee);
            })
        );

        $provider = $this->app->make(SchoolDataProvider::class);

        $this->assertEquals($employee, $provider->getEmployee($employeeId));
    }
}
