@extends('backend.master')

@push('style')
    <style>
        /* Card styling */
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .card-header {
            background: linear-gradient(135deg, #007bff, #6610f2);
            color: #fff;
            font-weight: 600;
            border-radius: 12px 12px 0 0;
        }

        /* Form labels */
        .form-label {
            font-weight: 500;
            color: #333;
        }

        /* Button styling */
        .btn-primary {
            background: #6610f2;
            border-color: #6610f2;
        }

        .btn-primary:hover {
            background: #007bff;
            border-color: #007bff;
        }

        /* Accordion styling */
        .accordion-button {
            background: #f8f9fa;
            color: #333;
            font-weight: 500;
        }

        .accordion-button:not(.collapsed) {
            background: #e7f1ff;
            color: #007bff;
        }

        .accordion-body {
            background: #fff;
            color: #555;
        }

        /* Responsive tweaks */
        @media (max-width: 992px) {

            .col-lg-5,
            .col-lg-7 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
@endpush

@section('title', 'FAQ Pages')

@section('content')
    <main class="app-content content py-4">
        <div class="container-fluid">

           <div class="row g-4">

    <!-- Create FAQ Form (Top Card) -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-1">Create FAQ</h4>
                <p class="mb-0 opacity-75 small">Fill out the form to add a new FAQ</p>
            </div>
            <div class="card-body">
                <form id="createfaq" action="{{ route('faq.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Question</label>
                        <textarea name="que" class="form-control" placeholder="Type your question..." rows="2">{{ old('que') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Answer</label>
                        <textarea name="ans" class="ck-editor form-control @error('ans') is-invalid @enderror" rows="4">{{ old('ans') }}</textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-plus me-1"></i> Create
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- FAQ List (Bottom Card) -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">FAQ List</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th>Question</th>
                                <th>Answer</th>
                                <th class="text-end" style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($faqs as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->que }}</td>
                                    <td>{!! Str::limit(strip_tags($item->ans), 60, '...') !!}</td>
                                    <td class="text-end">
                                        <a href="{{ route('faq.edit', $item->id) }}" class="btn btn-sm btn-info me-1">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <form action="{{ route('faq.destroy', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this?')">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        No FAQs found.
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
