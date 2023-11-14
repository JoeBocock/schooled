<?php

declare(strict_types=1);

namespace App\Services\Wonde;

use App\Contracts\SchoolDataProvider;
use GuzzleHttp\Exception\ClientException;
use Psr\Log\LoggerInterface;
use Wonde\Client;

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

    public function getEmployee(string $id): array|null
    {
        return $this->fetchEmployee($id);
    }

    public function getClassesForEmployee(string $id): array|null
    {
        $employee = $this->fetchEmployee($id, ['classes']);

        // TODO: Class DTO to normalise the response.
        return $employee && isset($employee['classes'])
            ? $employee['classes']['data']
            : null;
    }

    public function getStudentsForClass(string $id): array|null
    {
        $employee = $this->fetchEmployee($id, ['classes']);

        // TODO: Class DTO to normalise the response.
        return $employee && isset($employee['classes'])
            ? $employee['classes']['data']
            : null;
    }

    private function fetchEmployee(string $id, array $includes = []): array|null
    {
        $employee = null;

        $this->logger->debug('wonde-school-data-provider-fetch-employee', [
            'id' => $id,
            'includes' => $includes,
        ]);

        try {
            $employee = $this->client->school($this->schoolId)->employees->get($id, $includes);
        } catch (ClientException $e) {
            $this->logger->info(
                'wonde-school-data-provider-get-employee-4xx',
                ['exception' => $e]
            );
        } catch (\Throwable $th) {
            $this->logger->error(
                'wonde-school-data-provider-get-employee-failure',
                ['exception' => $th]
            );
        }

        // TODO: Employee DTO to normalise the response.
        return $employee
            ? json_decode(json_encode($employee), true)
            : null;
    }
}
