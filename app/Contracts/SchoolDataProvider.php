<?php

declare(strict_types=1);

namespace App\Contracts;

interface SchoolDataProvider
{
    public function getEmployee(string $id): array|null;
}
