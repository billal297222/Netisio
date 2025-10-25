<?php

namespace App\Http\Controllers\Backend;
use App\Models\FAQ;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FqaController extends Controller
{ 
    //
    public function index()
    {
        $data['faqs'] = FAQ::all();
        return view('backend.layouts.faq.index',$data);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'que' => 'required|string|max:255',
            'ans' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first())->withInput();
        }

        try {
            FAQ::create($data);
            return back()->with('success', 'faq successfully created');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
    
        $faqs = FAQ :: findOrFail($id);
       return view('backend.layouts.faq.edit',compact('faqs'));
    }

    public function update(Request $request,$id)
    {
        $data = $request->only(['que', 'ans']);
       $faq = FAQ::find($id);
        $validator = Validator::make($data, [
            'que' => 'required|string|max:255',
            'ans' => 'required|string',
         ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first())->withInput();
        }
        // dd($faq);
        // dd($data);
           
    //    $faq = FAQ::find($request->$id);
    //    $faq->update($data);
        try {
           FAQ::find($request->id)->update($data);
            return redirect()->route('fqa.index')->with('success', 'Fqa updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
         
    }

     public function destroy($id)
    {
        $delete = FAQ::find($id)->delete();
        if ($delete) {
            return back()->with('success', 'Deleted Successfully');
        } else {
            return back()->with('error', 'Try Again!');
        }
    }
}
