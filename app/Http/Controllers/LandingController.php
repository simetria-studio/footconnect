<?php

namespace App\Http\Controllers;

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

        return view('welcome', [
            'planGroups' => $planGroups,
            'annualDiscount' => config('plans.annual_discount_percent'),
        ]);
    }
}
