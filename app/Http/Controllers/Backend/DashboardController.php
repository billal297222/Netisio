<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Kid;
use App\Models\ParentModel;
use App\Models\Saving;
use App\Models\Task;
use App\Models\WeeklyPayment;
use App\Models\Backend;

class DashboardController extends Controller
{
    public function index()
    {

        $totalParents = ParentModel::count();
        $totalFamilies = Family::count();
        $totalKids = Kid::count();

        $totalTasks = Task::count();

        $totalGoals = Saving::count();

        $totalWeeklyPayments = WeeklyPayment::count();

        $backend = Backend::first();
        $monthlyLimit = $backend ? $backend->monthly_limit : 0;

        return view('backend.layouts.dashboard', compact(
            'totalParents',
            'totalFamilies',
            'totalKids',
            'totalTasks',
            'totalGoals',
            'totalWeeklyPayments',
            'monthlyLimit'
        ));
    }
}
