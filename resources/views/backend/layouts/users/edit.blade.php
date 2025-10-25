@extends('backend.master')
@section('title', 'Edit User')

@section('content')
    <div class="app-content content">
        <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $user->id }}">
            <div class="row">
                <div class="col-lg-12 m-auto">
                    {{-- Avatar Card --}}
                    {{-- <div class="card card-body mb-3">
                    <h4 class="mb-4">User <span>Avatar</span></h4>
                    <div class="row mb-2">
                        <label class="col-3 col-form-label">Avatar</label>
                        <div class="col-9">
                            <img id="avatarPreview" class="mb-2" width="80" height="80"
                                 src="{{ asset($user->avatar ?? 'default/user.png') }}" alt="Avatar"><br>
                            <input type="file" name="avatar" class="form-control-sm"
                                   oninput="avatarPreview.src=window.URL.createObjectURL(this.files[0])">
                        </div>
                    </div>
                </div> --}}

                    {{-- User Info Card --}}
                    <div class="card card-body mb-3">
                        <h4 class="mb-4">User <span>Info</span></h4>

                        <div class="row mb-2">
                            <label class="col-3 col-form-label">Name</label>
                            <div class="col-9">
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $user->name) }}" placeholder="Name">
                            </div>
                        </div>



                        <div class="row mb-2">
                            <label class="col-3 col-form-label">Email</label>
                            <div class="col-9">
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" placeholder="Email">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-3 col-form-label">Phone Number</label>
                            <div class="col-9">
                                <input type="text" name="phone_number" class="form-control"
                                    value="{{ old('phone_number', $user->phone_number) }}" placeholder="Phone Number">
                                @error('phone_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-2">
                            <label class="col-3 col-form-label">New Password</label>
                            <div class="col-9">
                                <input type="password" name="password" class="form-control"
                                    placeholder="Enter new password">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-3 col-form-label">Confirm Password</label>
                            <div class="col-9">
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Confirm new password">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line"></i> Update
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
