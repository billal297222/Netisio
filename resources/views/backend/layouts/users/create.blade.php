@extends('backend.master')
@section('title', 'Create User')

@section('content')
<div class="app-content content">
    <div class="container-fluid">
        <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card shadow-sm rounded-4 border-0 p-4">

                <div class="card-header text-white rounded-3 mb-4 ">
                    <h4 class="mb-0">Create New User</h4>
                    <p class="text-white small opacity-75 mb-0">
                        Fill the details below to create a new user
                    </p>
                </div>

                <div class="card-body">

                    <!-- User Role -->
                    <div class="mb-3 row align-items-center">
                        <label class="col-md-2 col-form-label">User Role</label>
                        <div class="col-md-10">
                            <select name="role" class="form-control">
                                <option value="">-- Select User Role --</option>
                                @foreach ($roles as $r)
                                    <option value="{{ $r->name }}" {{ old('role') == $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="mb-3 row align-items-center">
                        <label class="col-md-2 col-form-label">Name</label>
                        <div class="col-md-10">
                            <input type="text" name="name" class="form-control" placeholder="Full Name" value="{{ old('name') }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3 row align-items-center">
                        <label class="col-md-2 col-form-label">Email</label>
                        <div class="col-md-10">
                            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="mb-3 row align-items-center">
                        <label class="col-md-2 col-form-label">Phone Number</label>
                        <div class="col-md-10">
                            <input type="text" name="phone_number" class="form-control" placeholder="Phone Number" value="{{ old('phone_number') }}">
                            @error('phone_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Profile -->
                    {{-- <div class="mb-3 row align-items-center">
                        <label class="col-md-2 col-form-label">Profile</label>
                        <div class="col-md-10">
                            <input type="file" name="profile" class="form-control">
                             @error('profile')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div> --}}

  <!-- Password -->
<div class="mb-3 row align-items-center">
    <label class="col-md-2 col-form-label">Password</label>
    <div class="col-md-10 position-relative">
        <input type="password" id="password" name="password" class="form-control" placeholder="Enter password...">
        <span onclick="togglePassword()"
              class="position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer">
            <i id="eyeIconPassword" class="mdi mdi-eye-off"></i>
        </span>
        @error('password')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

<!-- Confirm Password -->
<div class="mb-3 row align-items-center">
    <label class="col-md-2 col-form-label">Confirm Password</label>
    <div class="col-md-10 position-relative">
        <input type="password" id="confirmPassword" name="password_confirmation" class="form-control" placeholder="Confirm password...">
        <span onclick="toggleConfirmPassword()"
              class="position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer">
            <i id="eyeIconConfirm" class="mdi mdi-eye-off"></i>
        </span>
        @error('password_confirmation')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>



                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold">
                            <i class="ri-save-line me-2"></i> Create User
                        </button>
                    </div>

                </div>
            </div>
        </form>

        {{-- Display any general session errors --}}
        @if(session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

    </div>
</div>

@push('script')
<script>
function togglePassword() {
    const field = document.getElementById('password');
    const icon  = document.getElementById('eyeIconPassword');

    if(field.type === "password") {
        field.type = "text";
        icon.classList.replace("mdi-eye-off", "mdi-eye");
    } else {
        field.type = "password";
        icon.classList.replace("mdi-eye", "mdi-eye-off");
    }
}

function toggleConfirmPassword() {
    const field = document.getElementById('confirmPassword');
    const icon = document.getElementById('eyeIconConfirm');

    if(field.type === "password") {
        field.type = "text";
        icon.classList.replace("mdi-eye-off", "mdi-eye");
    } else {
        field.type = "password";
        icon.classList.replace("mdi-eye", "mdi-eye-off");
    }
}

</script>


@endpush


@endsection
