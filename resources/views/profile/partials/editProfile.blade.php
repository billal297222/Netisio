@extends('backend.master')

@section('title', 'Edit Profile')

@section('content')
<main class="app-content content">
    <div >
        <div class="col-lg-6 col-md-8 col-12">
            <div class="card shadow-sm rounded-4 border-0">

                {{-- Card Header with Avatar --}}
                <div class="card-header text-center bg-light rounded-top-4">
                    <label class="position-relative d-inline-block">
                        <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('images/default-avatar.png') }}"
                             alt="Avatar"
                             class="rounded-circle border border-2 shadow"
                             width="100" height="100">
                        <input type="file" name="avatar" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer rounded-circle">
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-dark bg-opacity-25 rounded-circle opacity-0 hover-opacity-100 transition">
                            <i data-feather="camera" class="text-white fs-4"></i>
                        </div>
                    </label>
                    <p class="mt-2 mb-0 small text-muted">Click avatar to change</p>
                    <x-input-error :messages="$errors->get('avatar')" class="mt-1 text-danger small" />
                </div>

                {{-- Card Body --}}
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input id="name" name="name" type="text" 
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required autofocus>
                            <x-input-error :messages="$errors->get('name')" class="mt-1 text-danger small" />
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="email" 
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-danger small" />

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <p class="small mt-2 text-warning">
                                    Your email is unverified. 
                                    <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline">Resend verification</button>
                                </p>
                            @endif
                        </div>

                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>

                        @if (session('status') === 'profile-updated')
                            <p class="mt-2 small text-success text-center">Saved.</p>
                        @endif
                    </form>
                </div>

            </div>
        </div>
    </div>
</main>
@endsection
