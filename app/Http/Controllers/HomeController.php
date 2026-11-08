<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Faq;
use App\Models\Post;
use App\Models\PricingPlan;
use App\Models\Project;
use App\Models\Repository;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $projects = Project::published()->get();

        return view('home', [
            'projects' => $projects,
            'projectCount' => $projects->count(),
            'caseStudies' => $projects->map->toCaseStudy()->values()->all(),
            'brands' => Brand::published()->get(),
            'repositories' => Repository::where('is_published', true)->ordered()->get(),
            'services' => Service::where('is_published', true)->ordered()->get(),
            'experiences' => Experience::ordered()->get(),
            'educations' => Education::ordered()->get(),
            'plans' => PricingPlan::ordered()->get(),
            'testimonials' => Testimonial::where('is_published', true)->ordered()->get(),
            'faqs' => Faq::where('is_published', true)->ordered()->get(),
            'posts' => Post::published()->take(3)->get(),
        ]);
    }
}
