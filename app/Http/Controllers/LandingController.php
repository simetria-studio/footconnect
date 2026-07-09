<?php

namespace App\Http\Controllers;

use App\Models\MarketingBanner;
use App\Models\NewsPost;
use App\Models\PlanPrice;

class LandingController extends Controller
{
    public function __invoke()
    {
        $planGroups = collect(config('plans.groups'))->map(function (array $group, string $key) {
            return array_merge($group, [
                'key' => $key,
                'monthly' => PlanPrice::getByKey($key.'_monthly'),
                'yearly' => PlanPrice::getByKey($key.'_yearly'),
            ]);
        });

        $banners = MarketingBanner::query()
            ->active()
            ->forAudience(null)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        $news = NewsPost::query()
            ->published()
            ->forAudience(null)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        return view('welcome', [
            'planGroups' => $planGroups,
            'annualDiscount' => config('plans.annual_discount_percent'),
            'banners' => $banners,
            'news' => $news,
        ]);
    }
}
