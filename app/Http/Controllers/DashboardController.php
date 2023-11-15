<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\SchoolDataProvider;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private SchoolDataProvider $schoolDataProvider)
    {
    }

    public function show(Request $request): View|Factory
    {
        $classes = $this->schoolDataProvider->getEmployeeClasses($request->user()?->provider_id);

        return view('dashboard', [
            'classes' => $classes,
        ]);
    }
}
