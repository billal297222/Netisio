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

@section('title', 'Family Details')

@section('content')
<main class="app-content content py-4">
    <div class="container-fluid">

        <div class="row g-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Family: {{ $family->name }}</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">SL</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th class="text-end" style="width: 150px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Parent --}}
                                    <tr>
                                        <td>1</td>
                                        <td>{{ $family->parent->full_name ?? 'N/A' }}</td>
                                        <td>Parent</td>
                                        <td>
                                            @if($family->parent->status ?? false)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-end d-flex justify-content-end gap-1">
                                            {{-- Status Toggle Button --}}
                                            <form action="{{ route('toggle.status', ['type' => 'parent', 'id' => $family->parent->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $family->parent->status ? 'btn-success' : 'btn-secondary' }}">
                                                    @if($family->parent->status ?? false)
                                                        <i class="mdi mdi-toggle-switch"></i>
                                                    @else
                                                        <i class="mdi mdi-toggle-switch-off"></i>
                                                    @endif
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    {{-- Kids --}}
                                    @forelse($family->kids as $key => $kid)
                                        <tr>
                                            <td>{{ $key + 2 }}</td>
                                            <td>{{ $kid->full_name ?? $kid->username }}</td>
                                            <td>Kid</td>
                                            <td>
                                                @if($kid->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-end d-flex justify-content-end gap-1">
                                                {{-- Status Toggle --}}
                                                <form action="{{ route('toggle.status', ['type' => 'kid', 'id' => $kid->id]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm {{ $kid->status ? 'btn-success' : 'btn-secondary' }}">
                                                        @if($kid->status)
                                                            <i class="mdi mdi-toggle-switch"></i>
                                                        @else
                                                            <i class="mdi mdi-toggle-switch-off"></i>
                                                        @endif
                                                    </button>
                                                </form>

                                                {{-- Delete --}}
                                                <form action="{{ route('kids.destroy', $kid->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this kid?')">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">No kids found in this family.</td>
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
