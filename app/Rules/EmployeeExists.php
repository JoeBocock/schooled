<?php

declare(strict_types=1);

namespace App\Rules;

use App\Contracts\SchoolDataProvider;
use Illuminate\Contracts\Validation\ValidationRule;

class EmployeeExists implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $schoolDataProvider = app()->make(SchoolDataProvider::class);

        if (! $schoolDataProvider->getEmployee($value)) {
            $fail("No employee with identifier {$value} found.");
        }
    }
}
