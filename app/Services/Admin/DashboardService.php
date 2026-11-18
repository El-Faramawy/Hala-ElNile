<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Admin;
use App\Models\City;
use App\Models\ContactMessage;
use App\Models\ContractTracker;
use App\Models\Faq;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobCategory;
use App\Models\Traveler;

class DashboardService
{
    /**
     * Compile statistics for admin dashboard.
     *
     * @return array<string, mixed>
     */
    public function getDashboardStats(): array
    {
        return [
            'total_admins' => Admin::count(),
            'total_job_categories' => JobCategory::count(),
            'total_cities' => City::count(),
            'total_jobs' => Job::count(),
            'total_applications' => JobApplication::count(),
            'total_contracts' => ContractTracker::count(),
            'total_travelers' => Traveler::count(),
            'total_messages' => ContactMessage::count(),
            'total_faqs' => Faq::count(),
            'recent_applications' => JobApplication::with(['job', 'city'])->latest()->take(5)->get(),
            'recent_messages' => ContactMessage::with('city')->latest()->take(5)->get(),
        ];
    }
}
