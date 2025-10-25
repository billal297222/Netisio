@extends('backend.master')

@push('style')
<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }
    .card-header {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: #fff;
        font-weight: 600;
        border-radius: 12px 12px 0 0;
    }
    .form-label {
        font-weight: 500;
        color: #333;
    }
    .btn-primary {
        background: #20c997;
        border-color: #20c997;
    }
    .btn-primary:hover {
        background: #28a745;
        border-color: #28a745;
    }
</style>
@endpush

@section('title', 'PDF Management')

@section('content')
<main class="app-content content py-4">
    <div class="container-fluid">
        <div class="row g-4">

            <!-- Create PDF Form -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-1">Upload PDF</h4>

                    </div>
                    <div class="card-body">
                        <form action="{{ route('pdf.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="custom_date" class="form-control" value="{{ old('custom_date') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control"
                                    placeholder="Enter PDF title" value="{{ old('title') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Short Description</label>
                                <textarea name="short_desc" class="form-control" rows="2"
                                    placeholder="Write a short description">{{ old('short_desc') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Upload PDF</label>
                                <input type="file" name="file" class="form-control" accept="application/pdf">
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-plus me-1"></i> Upload
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- PDF List -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">PDF List</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th>Title</th>
                                        <th>Short Description</th>
                                        <th>Date</th>
                                        <th>File</th>
                                        <th class="text-end" style="width: 150px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pdfs as $key => $pdf)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $pdf->title }}</td>
                                            <td>{{ Str::limit($pdf->short_desc, 50, '...') }}</td>
                                            <td>{{ $pdf->date->date_value ?? '-' }}</td>
                                            <td>
                                                <a href="{{ asset($pdf->file_path) }}" target="_blank" class="btn btn-sm btn-secondary">
                                                    <i class="mdi mdi-file-pdf"></i> View
                                                </a>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('pdf.edit', $pdf->id) }}" class="btn btn-sm btn-info me-1">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <form action="{{ route('pdf.destroy', $pdf->id) }}" method="GET" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this PDF?')">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3">
                                                No PDFs found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
