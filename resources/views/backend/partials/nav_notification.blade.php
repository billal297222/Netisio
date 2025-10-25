@extends('backend.master')

@push('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
<style>
    /* Make images square and nicely aligned */
    .preview-img {
        width: 100%;
        max-width: 150px;
        aspect-ratio: 1 / 1;
        object-fit: contain;
        border: 1px solid #ddd;
        padding: 5px;
        margin-bottom: 5px;
        border-radius: 8px;
        background-color: #f8f9fa;
    }
</style>
@endpush

@section('title', 'System Settings')

@section('content')
<div>
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">System Settings</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('system.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Left Column: Logo & Favicon -->
                    <div class="col-md-4 d-flex flex-column align-items-center">
                        <!-- Logo -->
                        <div class="mb-4 text-center">
                            <label class="form-label">Logo</label>
                            @if(!empty($system_settings->logo) && file_exists(public_path('logo/' . $system_settings->logo)))
                                <img src="{{ asset('logo/' . $system_settings->logo) }}" alt="Logo" class="preview-img">
                            @endif
                            <input type="file" name="logo" class="form-control dropify mt-2"
                                   data-allowed-file-extensions="jpg jpeg png" 
                                   data-max-file-size="2M">
                            @error('logo')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                        </div>

                        <!-- Favicon -->
                        <div class="mb-4 text-center">
                            <label class="form-label">Favicon</label>
                            @if(!empty($system_settings->favicon) && file_exists(public_path('favicon/' . $system_settings->favicon)))
                                <img src="{{ asset('favicon/' . $system_settings->favicon) }}" alt="Favicon" class="preview-img">
                            @endif
                            <input type="file" name="favicon" class="form-control dropify mt-2"
                                   data-allowed-file-extensions="jpg jpeg png ico" 
                                   data-max-file-size="1M">
                            @error('favicon')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <!-- Right Column: Other Fields -->
                    <div class="col-md-8">
                        @php
                            $fields = [
                                'System Title' => 'system_title',
                                'Short Title' => 'system_short_title',
                                'Tag Line' => 'tag_line',
                                'Company Name' => 'company_name',
                                'Email' => 'email',
                                'Copyright' => 'copyright',
                            ];
                        @endphp

                        @foreach ($fields as $label => $name)
                        <div class="mb-3">
                            <label class="form-label">{{ $label }}</label>
                            <input type="text" class="form-control" name="{{ $name }}"
                                   value="{{ $system_settings->$name ?? '' }}"
                                   placeholder="Enter {{ strtolower($label) }}">
                            @error($name)<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                        @endforeach

                        <!-- Phone -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Phone Code</label>
                                <select class="form-select" name="phone_code">
                                    <option value="+880" {{ $system_settings?->phone_code === '+880' ? 'selected' : '' }}>+880 (Bangladesh)</option>
                                    <option value="+91" {{ $system_settings?->phone_code === '+91' ? 'selected' : '' }}>+91 (India)</option>
                                    <option value="+1" {{ $system_settings?->phone_code === '+1' ? 'selected' : '' }}>+1 (USA)</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control" name="phone_number"
                                       value="{{ $system_settings->phone_number ?? '' }}" placeholder="Enter phone number">
                            </div>
                        </div>

                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary px-4">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
<script>
    $('.dropify').dropify();
</script>
@endpush
