<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\City\CityService;
use App\Services\Faq\FaqService;
use App\Services\Job\JobService;
use App\Services\JobCategory\JobCategoryService;
use App\Services\Setting\SettingService;
use App\Services\Traveler\TravelerService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(
        protected JobService $jobService,
        protected JobCategoryService $jobCategoryService,
        protected TravelerService $travelerService,
        protected FaqService $faqService,
        protected CityService $cityService,
        protected SettingService $settingService,
    ) {}

    public function index(Request $request): View
    {
        $settings = $this->settingService->getSettings();
        $categories = $this->jobCategoryService->getJobCategoriesForWebsite();
        $jobs = $this->jobService->getAllActive();
        $travelers = $this->travelerService->getAllActive();
        $faqs = $this->faqService->getFaqs();
        $cities = $this->cityService->getCities();

        return view('Site.index', compact(
            'settings',
            'categories',
            'jobs',
            'travelers',
            'faqs',
            'cities'
        ));
    }
}
