<?php

declare(strict_types=1);

namespace App\Contracts;

interface SchoolDataProvider
{
    public function getEmployee(string $id): array|null;

    public function getEmployeeClasses(string $id): array|null;

    /**
     * If $employeeId is provided, then it should only return a class that belongs
     * to said employee.
     */
    public function getClassWithStudents(string $id, string $employeeId = null): array|null;
}
