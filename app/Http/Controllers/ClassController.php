<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\SchoolDataProvider;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function __construct(private SchoolDataProvider $schoolDataProvider)
    {
    }

    public function show(string $id, Request $request): View|Factory
    {
        $class = $this->schoolDataProvider->getClassWithStudents($id, $request->user()->provider_id);

        if (! $class) {
            abort(404);
        }

        $class['students']['data'] = collect($class['students']['data'])
            ->sortBy('surname')
            ->toArray();

        return view('resources.class.show', [
            'class' => $class,
        ]);
    }
}
