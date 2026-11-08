<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Support\CountsViews;
use App\Support\OgImage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    use CountsViews;

    public function __invoke(Request $request, Project $project): View
    {
        abort_unless($project->is_published, 404);
        $this->countView($request, $project);

        $case = $project->toCaseStudy();
        $next = $project->nextProject();

        return view('projects.show', [
            'case' => $case,
            'next' => $next?->toCaseStudy(),
            'ogImage' => OgImage::make(
                __('site.case.label').' · '.$case['year'],
                $case['title'],
                $case['result'],
                $project->image ? storage_path('app/public/'.$project->image) : null,
            ),
        ]);
    }
}
