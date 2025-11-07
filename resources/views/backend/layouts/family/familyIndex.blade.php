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

@section('title', 'Family Management')

@section('content')
<main class="app-content content py-4">
    <div class="container-fluid">
        <div class="row g-4">

            <!-- Families List -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Families</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">SL</th>
                                        <th>Family Name</th>
                                        <th>Total Members</th>
                                        <th class="text-center" style="width: 150px;">View</th>
                                        <th class="text-end" style="width: 150px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($families as $key => $family)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $family->name }}</td>
                                            <td>{{ $family->kids_count + 1 }}</td> {{-- +1 for parent --}}
                                            <td class="text-center">
                                                <a href="{{ route('family.show', $family->id) }}" class="btn btn-sm btn-success">
                                                    <i class="mdi mdi-eye"></i> View
                                                </a>
                                            </td>
                                            <td class="text-end">
                                                <form action="{{ route('family.delete', $family->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this family and all related data?')">
                                                        <i class="mdi mdi-delete"></i> 
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">
                                                No families found.
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
