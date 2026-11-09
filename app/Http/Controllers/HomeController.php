<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Post;
use App\Models\Project;
use App\Models\Repository;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $projects = Project::published()->get();

        // Hizmetler, yetkinlikler, hakkımda, deneyim, fiyatlar, referanslar ve
        // SSS artık kendi sayfalarında: bkz. config/nav.php + PageController.
        return view('home', [
            'projects' => $projects,
            'projectCount' => $projects->count(),
            'caseStudies' => $projects->map->toCaseStudy()->values()->all(),
            'brands' => Brand::published()->get(),
            'repositories' => Repository::where('is_published', true)->ordered()->get(),
            'posts' => Post::published()->take(3)->get(),
        ]);
    }
}
