@extends('backend.master')

@section('title', 'Change Password')

@section('content')
<main class="app-content content">
    <div class="row justify-content-center mt-4">
        <div class="col-lg-6 col-md-8 col-12">
            <div class="card shadow-sm rounded-4 border-0">

                {{-- Card Header --}}
                <div class="card-header bg-light rounded-top-4 text-center">
                    <h4 class="mb-1">Update Password</h4>
                    <p class="small text-muted mb-0">Ensure your account is using a long, random password to stay secure.</p>
                </div>

                {{-- Card Body --}}
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input id="current_password" name="current_password" type="password" class="form-control" autocomplete="current-password">
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1 text-danger small"/>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input id="password" name="password" type="password" class="form-control" autocomplete="new-password">
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1 text-danger small"/>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
                            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1 text-danger small"/>
                        </div>

                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>

                        @if (session('status') === 'password-updated')
                            <p class="mt-2 small text-success text-center">Saved.</p>
                        @endif
                    </form>
                </div>

            </div>
        </div>
    </div>
</main>
@endsection
