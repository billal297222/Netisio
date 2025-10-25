@extends('backend.master')

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
    <style>
        /* Card style */
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #343a40;
            color: #fff;
            font-weight: 600;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        /* Image Upload Style */
        .image-upload-wrapper {
            position: relative;
            cursor: pointer;
            width: 100%;
            max-width: 200px;
            margin-bottom: 0.5rem;
        }

        .image-upload-wrapper img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
            display: block;
        }

        .image-hover {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.3);
            opacity: 0;
            border-radius: 8px;
            transition: opacity 0.2s;
        }

        .image-upload-wrapper:hover .image-hover {
            opacity: 1;
        }

        .image-hover i {
            color: #fff;
            font-size: 2rem;
        }

        .form-label {
            font-weight: 500;
        }

        .text-muted {
            font-size: 0.85rem;
        }
    </style>
@endpush

@section('title', 'Admin Settings')

@section('content')
    <div class=" container-fluid px-5">
        <div class="card my-4">
            <div class="card-header">
                <h3 class="mb-0">Admin Settings</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Left Column: Admin Logo & Favicon -->
                        <div class="col-md-3 d-flex flex-column align-items-start gap-3">
                            <!-- Admin Logo -->
                            <div class="image-upload-wrapper text-start">
                                <label class="position-relative d-inline-block">
                                    <img id="adminLogoPreview"
                                        src="{{ !empty($system_settings->admin_logo) ? asset($system_settings->admin_logo) : asset('admin_logo/default_logo.jpeg') }}"
                                        alt="Admin Logo"
                                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                                    <input type="file" name="admin_logo" id="adminLogoInput"
                                        class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer rounded"
                                        onchange="previewImage(this, 'adminLogoPreview')">
                                    <div class="image-hover d-flex justify-content-center align-items-center">
                                        <i class="mdi mdi-camera text-white fs-4"></i>
                                    </div>
                                </label>
                                <p class="mt-2 mb-0 text-muted">Click to upload admin logo</p>
                                @error('admin_logo')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Admin Favicon -->
                            <div class="image-upload-wrapper text-start">
                                <label class="position-relative d-inline-block">
                                    <img id="adminFaviconPreview"
                                        src="{{ !empty($system_settings->admin_favicon) ? asset($system_settings->admin_favicon) : asset('admin_favicon/default_favicon.jpeg') }}"
                                        alt="Admin Favicon"
                                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                                    <input type="file" name="admin_favicon" id="adminFaviconInput"
                                        class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer rounded"
                                        onchange="previewImage(this, 'adminFaviconPreview')">
                                    <div class="image-hover d-flex justify-content-center align-items-center">
                                        <i class="mdi mdi-camera text-white fs-4"></i>
                                    </div>
                                </label>
                                <p class="mt-2 mb-0 text-muted">Click to upload admin favicon</p>
                                @error('admin_favicon')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <!-- Right Column: Admin Details -->
                        <div class="col-md-9">
                            @php
                                $fields = [
                                    'Admin Title' => 'admin_title',
                                    'Admin Short Title' => 'admin_short_title',
                                    'Copyright Text' => 'admin_copyright_text',
                                ];
                            @endphp

                            @foreach ($fields as $label => $name)
                                <div class="mb-3">
                                    <label class="form-label">{{ $label }}</label>
                                    <input type="text" class="form-control" name="{{ $name }}"
                                        value="{{ $system_settings->$name ?? '' }}"
                                        placeholder="Enter {{ strtolower($label) }}">
                                    @error($name)
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach

                            <!-- Submit Button -->
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary px-4">Update Admin Settings</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(previewId).src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
