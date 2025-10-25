@extends('backend.master')

@section('title', 'Users List')

@section('content')
    <div class="app-content content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center text-white">
                <h4 class="mb-0">Users List</h4>
                <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm fw-bold">
                    <i class="ri-add-line me-1"></i> Create User
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive mt-4 p-4">
                    <table class="table table-hover table-bordered" id="data-table">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                {{-- <th>Status</th> --}}
                                <th>Phone Number</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        {{-- <form action="{{ route('user.status', $user->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-sm {{ $user->status === 'active' ? 'btn-success' : 'btn-danger' }}">
                                                {{ $user->status === 'active' ? 'Active' : 'Inactive' }}
                                            </button>
                                        </form> --}}

                                        {{ $user->phone_number }}
                                    </td>
                                    <td>

                                        {{-- <a href="{{ route('user.edit', $user->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a> --}}
                                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-info">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>

                                        {{-- <form action="{{ route('user.status', $user->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-sm {{ $user->status === 'active' ? 'btn-success' : 'btn-secondary' }}">
                                                <i
                                                    class="mdi {{ $user->status === 'active' ? 'mdi-check' : 'mdi-close' }}"></i>
                                            </button>
                                        </form> --}}

                                        <a href="{{ route('user.destroy', $user->id) }}"
                                            onclick="return confirm('Are you sure you want to delete this?')"
                                            class="btn btn-sm btn-danger">
                                            <i class="mdi mdi-delete"></i>
                                        </a>
                                    </td>


                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
