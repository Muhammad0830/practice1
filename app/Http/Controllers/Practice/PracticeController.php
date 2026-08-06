<?php

namespace App\Http\Controllers\Practice;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class PracticeController extends Controller
{
    public function get(): Response
    {
        return Inertia::render('Practice', [
            'data' => [
                ['id' => 0, 'title' => 'title1'],
                ['id' => 1, 'title' => 'title2'],
            ],
        ]);
    }
}
