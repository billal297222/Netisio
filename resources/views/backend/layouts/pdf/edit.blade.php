@extends('backend.master')
@section('title', 'Edit PDF')

@section('content')
    <div class="app-content content">
        <form action="{{ route('pdf.update', $pdf->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $pdf->id }}">
            <div class="row">
                <div class="col-lg-12 m-auto">

                    {{-- PDF Info Card --}}
                    <div class="card card-body mb-3 shadow-sm">
                        <h4 class="mb-4">PDF <span>Info</span></h4>

                        <!-- Date Field -->
                        <div class="row mb-3">
                            <label class="col-3 col-form-label">Date</label>
                            <div class="col-9">
                                <input type="date" name="custom_date" class="form-control"
                                    value="{{ old('custom_date', $pdf->date->date_value ?? '') }}">
                            </div>
                        </div>

                        {{-- Title --}}
                        <div class="row mb-3">
                            <label class="col-3 col-form-label">Title</label>
                            <div class="col-9">
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title', $pdf->title) }}" placeholder="PDF Title">
                            </div>
                        </div>

                        {{-- Short Description --}}
                        <div class="row mb-3">
                            <label class="col-3 col-form-label">Short Description</label>
                            <div class="col-9">
                                <textarea name="short_desc" class="form-control" rows="3" placeholder="Short description...">{{ old('short_desc', $pdf->short_desc) }}</textarea>
                            </div>
                        </div>

                        {{-- Upload New PDF --}}
                        <div class="row mb-3">
                            <label class="col-3 col-form-label">Upload New PDF</label>
                            <div class="col-9">
                                <input type="file" id="pdfInput" name="file" class="form-control"
                                    accept="application/pdf">

                                {{-- Current PDF --}}
                                <div class="mt-3">
                                    <p class="small">Current PDF: <a href="{{ asset($pdf->file_path) }}" target="_blank"
                                            class="text-primary">View PDF</a></p>
                                </div>

                                {{-- New PDF Preview --}}
                                <div id="newPdfWrapper" class="mt-3"
                                    style="border:1px solid #ccc; width:100%; max-height:500px; overflow:auto; display:none;">
                                    <canvas id="newPdfPreview" style="width:100%; height:auto;"></canvas>
                                </div>

                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line"></i> Update PDF
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>

    {{-- PDF.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.12.313/pdf.min.js"></script>

    <script>
        // Set PDF.js worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.12.313/pdf.worker.min.js";

        const pdfInput = document.getElementById('pdfInput');
        const newPdfWrapper = document.getElementById('newPdfWrapper');
        const newPdfCanvas = document.getElementById('newPdfPreview');

        pdfInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type === "application/pdf") {
                // Show the wrapper
                newPdfWrapper.style.display = 'block';

                const reader = new FileReader();
                reader.onload = function(evt) {
                    const arrayBuffer = evt.target.result;

                    pdfjsLib.getDocument({
                        data: arrayBuffer
                    }).promise.then(pdf => {
                        pdf.getPage(1).then(page => {
                            const scale = 1.2;
                            const viewport = page.getViewport({
                                scale
                            });

                            newPdfCanvas.width = viewport.width;
                            newPdfCanvas.height = viewport.height;

                            const context = newPdfCanvas.getContext('2d');
                            page.render({
                                canvasContext: context,
                                viewport: viewport
                            });
                        });
                    }).catch(err => console.error('PDF render error:', err));
                };
                reader.readAsArrayBuffer(file);
            } else {
                newPdfWrapper.style.display = 'none';
            }
        });
    </script>
@endsection
