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
use Illuminate\Http\Request;

class ParentController extends Controller
{

    public function index()
    {
        $families = Family::withCount('kids')->get();
        return view('backend.layouts.family.familyIndex', compact('families'));
    }


    public function show($id)
    {
        $family = Family::with(['parent', 'kids'])->findOrFail($id);
        return view('backend.layouts.family.family', compact('family'));
    }


    public function delete($id)
    {
        $family = Families::findOrFail($id);

        $family->kids()->delete();

        $family->parent()->delete();

        $family->delete();

        return redirect()->route('family.index')->with('success', 'Family and all related data deleted successfully.');
    }


public function toggleStatus($type, $id)
{

    if ($type === 'parent') {
        $model = ParentModel::findOrFail($id);
    } elseif ($type === 'kid') {
        $model = Kid::findOrFail($id);
    } else {
        return redirect()->back()->with('error', 'Invalid type');
    }


    $model->status = !$model->status;
    $model->save();

    $message = ucfirst($type) . ' status updated successfully';
    return redirect()->back()->with('success', $message);
}

public function destroy($id)
{
    $kid = Kid::findOrFail($id);

    $kid->delete();

    return redirect()->back()->with('success', 'Kid deleted successfully.');
}

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


