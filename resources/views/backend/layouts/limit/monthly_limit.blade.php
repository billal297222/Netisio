@extends('backend.master')

@section('title', 'Edit Monthly Limit')

@push('style')
<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: #fff;
        font-weight: 600;
        font-size: 1.2rem;
        border-radius: 12px 12px 0 0;
        padding: 15px 20px;
    }

    .card-body {
        padding: 25px;
    }

    .form-label {
        font-weight: 500;
    }

    .btn-success {
        background: #20c997;
        border-color: #20c997;
        transition: all 0.3s;
    }

    .btn-success:hover {
        background: #28a745;
        border-color: #28a745;
    }

    .alert-success {
        border-radius: 8px;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="container py-10">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-header text-center">
                    Edit Monthly Limit
                </div>
                <div class="card-body">
                    <form action="{{ route('backend.update') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="monthly_limit" class="form-label">Monthly Limit</label>
                            <input type="number" name="monthly_limit" id="monthly_limit" class="form-control form-control-lg" step="0.01" value="{{ $backend->monthly_limit ?? 10000.00 }}" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">Update Limit</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
