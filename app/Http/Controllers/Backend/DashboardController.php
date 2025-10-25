<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pdf;

class DashboardController extends Controller
{
    public function index()
    {
          $totalUsers = User::where('admin', 0)->count();
        $totalPdfs = Pdf::count();

        return view('backend.layouts.dashboard', compact('totalUsers', 'totalPdfs'));
    }
}
