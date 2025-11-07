<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\Backend;

class MonthlyController extends Controller
{

    public function edit()
    {
        $backend = Backend::first();
        return view('backend.layouts.limit.monthly_limit', compact('backend'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'monthly_limit' => 'required|numeric|min:0',
        ]);

        $backend = Backend::first();
        if (!$backend) {
            $backend = new Backend();
        }

        $backend->monthly_limit = $request->monthly_limit;
        $backend->save();

        return redirect()->back()->with('success', 'Monthly limit updated successfully.');
    }
}
