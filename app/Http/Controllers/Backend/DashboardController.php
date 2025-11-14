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

        $weeklyPayments = WeeklyPayment::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->limit(7)
            ->get();

        $weeklyPaymentsChartData = [
            'labels' => $weeklyPayments->pluck('date'),
            'data' => $weeklyPayments->pluck('total')
        ];

        $tasksStatusData = [
            'labels' => ['Not Started', 'In Progress', 'Completed', 'Reward Collected'],
            'data' => [
                Task::where('status', 'not_started')->count(),
                Task::where('status', 'in_progress')->count(),
                Task::where('status', 'completed')->count(),
                Task::where('status', 'reward_collected')->count(),
            ]
        ];

        $savingGoalsStatusData = [
            'labels' => ['In Progress', 'Completed'],
            'data' => [
                Saving::where('status', 'in_progress')->count(),
                Saving::where('status', 'completed')->count(),
            ]
        ];

        return view('backend.layouts.dashboard', compact(
            'totalParents',
            'totalFamilies',
            'totalKids',
            'totalTasks',
            'totalGoals',
            'totalWeeklyPayments',
            'monthlyLimit',
            'weeklyPaymentsChartData',
            'tasksStatusData',
            'savingGoalsStatusData'
        ));
    }
}
