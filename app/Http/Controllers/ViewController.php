<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Support\CountsViews;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/** Ana sayfadaki vaka penceresi açıldığında projenin görüntülenmesini sayar. */
class ViewController extends Controller
{
    use CountsViews;

    public function __invoke(Request $request, Project $project): Response
    {
        abort_unless($project->is_published, 404);
        $this->countView($request, $project);

        return response()->noContent();
    }
}
