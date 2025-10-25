<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Pdf;
use App\Models\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    public function index()
    {
        $pdfs = Pdf::with('date')->join('dates', 'pdfs.date_id', '=', 'dates.id')
           ->orderBy('dates.date_value', 'desc')
           ->select('pdfs.*')
           ->get();

        //  return view('backend.layouts.pdf.index', $data);
        return view('backend.layouts.pdf.index', compact('pdfs'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'custom_date' => 'required|date',
            'title' => 'required|string|max:200',
            'short_desc' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf',
        ]);

        $date = Date::firstOrCreate(['date_value' => $request->custom_date]);

        // Handle file upload to public/File
        $path = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('File'), $filename);
            $path = 'File/' . $filename;
        }

        Pdf::create([
            'date_id' => $date->id,
            'title' => $request->title,
            'short_desc' => $request->short_desc,
            'file_path' => $path,
        ]);

        return redirect()->route('pdf.index')->with('success', 'PDF uploaded successfully.');
    }

    public function edit($id)
    {
        $pdf = Pdf::findOrFail($id);
        return view('backend.layouts.pdf.edit', compact('pdf'));
    }

    public function update(Request $request, $id)
    {
        $pdf = Pdf::findOrFail($id);

        $request->validate([
            'custom_date' => 'required|date',
            'title' => 'nullable|string|max:200',
            'short_desc' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf',
        ]);

            // Update or create the date record
            $date = Date::firstOrCreate(['date_value' => $request->custom_date]);
            $pdf->date_id = $date->id;

        if ($request->filled('title')) $pdf->title = $request->title;
        if ($request->filled('short_desc')) $pdf->short_desc = $request->short_desc;

        if ($request->hasFile('file')) {
            if ($pdf->file_path && file_exists(public_path($pdf->file_path))) {
                unlink(public_path($pdf->file_path));
            }
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('File'), $filename);
            $pdf->file_path = 'File/' . $filename;
        }

        $pdf->save();

        return redirect()->route('pdf.index')->with('success', 'PDF updated successfully.');
    }

    public function destroy($id)
    {
        $pdf = Pdf::findOrFail($id);

        if ($pdf->file_path && file_exists(public_path($pdf->file_path))) {
            unlink(public_path($pdf->file_path));
        }

        $pdf->delete();

        return redirect()->route('pdf.index')->with('success', 'PDF deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        if ($request->ids) {
            foreach ($request->ids as $id) {
                $pdf = Pdf::find($id);
                if ($pdf) {
                    if ($pdf->file_path && file_exists(public_path($pdf->file_path))) {
                        unlink(public_path($pdf->file_path));
                    }
                    $pdf->delete();
                }
            }
        }
        return redirect()->route('pdf.index')->with('success', 'Selected PDFs deleted successfully.');
    }
}
