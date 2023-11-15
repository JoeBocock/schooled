<?php

declare(strict_types=1);

namespace App\Services\Wonde;

use App\Contracts\SchoolDataProvider;
use GuzzleHttp\Exception\ClientException;
use Psr\Log\LoggerInterface;
use Wonde\Client;

// TODO: DTO to normalize public function responses.
class WondeSchoolDataProvider implements SchoolDataProvider
{
    private string $schoolId;

    public function __construct(private Client $client, private LoggerInterface $logger)
    {
        $this->schoolId = config('services.wonde.school');

        $this->logger->debug('wonde-school-data-provider-constructed', [
            'school_id' => $this->schoolId,
        ]);
    }

    public function getEmployee(string $id): array
    {
        return $this->fetch(
            fn () => $this->client->school($this->schoolId)->employees->get($id)
        ) ?? [];
    }

    public function getEmployeeClasses(string $id): array
    {
        $response = $this->fetch(
            fn () => $this->client->school($this->schoolId)->employees->get($id, ['classes'])
        );

        return isset($response['classes']) ? $response['classes']['data'] : [];
    }

    public function getClassWithStudents(string $id, string $employeeId = null): array
    {
        if ($employeeId) {
            if (! $classes = $this->getEmployeeClasses($employeeId)) {
                return [];
            }

            $classes = collect($classes)->filter(
                fn ($class) => $class['id'] === $id
            );

            if ($classes->count() === 0) {
                return [];
            }
        }

        $response = $this->fetch(
            fn () => $this->client->school($this->schoolId)->classes->get($id, ['students'])
        );

        return $response ?? [];
    }

    private function fetch(callable $operation): array
    {
        $response = [];

        try {
            $response = $operation();
        } catch (ClientException $e) {
            $this->logger->info(
                'wonde-school-data-provider-fetch-4xx',
                ['exception' => $e]
            );
        } catch (\Throwable $th) {
            $this->logger->error(
                'wonde-school-data-provider-fetch-failure',
                ['exception' => $th]
            );
        }

        return $response
            ? json_decode(json_encode($response), true)
            : [];
    }
}
