@extends('backend.master')

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
    <style>
        .image-upload-wrapper {
            width: 100%;
            position: relative;
            cursor: pointer;
        }

        .image-upload-wrapper img {
            width: 100%;
            height: 200px;
            /* Square-ish */
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
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
            transition: opacity 0.2s;
            border-radius: 8px;
        }

        .image-upload-wrapper:hover .image-hover {
            opacity: 1;
        }

        .image-hover i {
            color: #fff;
            font-size: 2rem;
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
                        <div class="col-md-4">
                            <!-- Logo -->
                            <!-- Logo -->
                            <div class="mb-4 text-start">
                                <label class="position-relative d-inline-block">
                                    <img id="logoPreview"
                                        src="{{ !empty($system_settings->logo) ? asset('logo/' . $system_settings->logo) : asset('images/default-logo.png') }}"
                                        alt="Logo" class="rounded border border-2 shadow" width="200" height="200">
                                    <input type="file" name="logo" id="logoInput"
                                        class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer rounded"
                                        onchange="previewImage(this, 'logoPreview')">
                                    <div
                                        class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-dark bg-opacity-25 rounded opacity-0 hover-opacity-100 transition">
                                        <i class="mdi mdi-camera text-white fs-4"></i>
                                    </div>
                                </label>
                                <p class="mt-2 mb-0 small text-muted">Click to upload logo</p>
                                @error('logo')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>






                            <!-- Favicon -->
                            <div class="mb-4 text-start">
                                <label class="position-relative d-inline-block">
                                    <img id="faviconPreview"
                                        src="{{ !empty($system_settings->favicon) ? asset('favicon/' . $system_settings->favicon) : asset('images/default-favicon.png') }}"
                                        alt="Favicon" class="rounded border border-2 shadow" width="200" height="200">
                                    <input type="file" name="favicon" id="faviconInput"
                                        class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer rounded"
                                        onchange="previewImage(this, 'faviconPreview')">
                                    <div
                                        class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-dark bg-opacity-25 rounded opacity-0 hover-opacity-100 transition">
                                        <i class="mdi mdi-camera text-white fs-4"></i>
                                    </div>
                                </label>
                                <p class="mt-2 mb-0 small text-muted">Click to upload favicon</p>
                                @error('favicon')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
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
                                    @error($name)
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach

                            <!-- Phone -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Phone Code</label>
                                    <select class="form-select" name="phone_code">
                                        <option value="+1"
                                            {{ $system_settings?->phone_code === '+1' ? 'selected' : '' }}>+1
                                            (USA)
                                        </option>
                                        <option value="+1"
                                            {{ $system_settings?->phone_code === '+1' ? 'selected' : '' }}>+1
                                            (Canada)</option>
                                        <option value="+44"
                                            {{ $system_settings?->phone_code === '+44' ? 'selected' : '' }}>+44
                                            (United Kingdom)</option>
                                        <option value="+91"
                                            {{ $system_settings?->phone_code === '+91' ? 'selected' : '' }}>+91
                                            (India)</option>
                                        <option value="+61"
                                            {{ $system_settings?->phone_code === '+61' ? 'selected' : '' }}>+61
                                            (Australia)</option>
                                        <option value="+81"
                                            {{ $system_settings?->phone_code === '+81' ? 'selected' : '' }}>+81
                                            (Japan)</option>
                                        <option value="+49"
                                            {{ $system_settings?->phone_code === '+49' ? 'selected' : '' }}>+49
                                            (Germany)</option>
                                        <option value="+33"
                                            {{ $system_settings?->phone_code === '+33' ? 'selected' : '' }}>+33
                                            (France)</option>
                                        <option value="+34"
                                            {{ $system_settings?->phone_code === '+34' ? 'selected' : '' }}>+34
                                            (Spain)</option>
                                        <option value="+39"
                                            {{ $system_settings?->phone_code === '+39' ? 'selected' : '' }}>+39
                                            (Italy)</option>
                                        <option value="+55"
                                            {{ $system_settings?->phone_code === '+55' ? 'selected' : '' }}>+55
                                            (Brazil)</option>
                                        <option value="+7"
                                            {{ $system_settings?->phone_code === '+7' ? 'selected' : '' }}>+7
                                            (Russia)</option>
                                        <option value="+86"
                                            {{ $system_settings?->phone_code === '+86' ? 'selected' : '' }}>+86
                                            (China)</option>
                                        <option value="+91"
                                            {{ $system_settings?->phone_code === '+91' ? 'selected' : '' }}>+91
                                            (India)</option>
                                        <option value="+62"
                                            {{ $system_settings?->phone_code === '+62' ? 'selected' : '' }}>+62
                                            (Indonesia)</option>
                                        <option value="+971"
                                            {{ $system_settings?->phone_code === '+971' ? 'selected' : '' }}>
                                            +971
                                            (United Arab Emirates)</option>
                                        <option value="+52"
                                            {{ $system_settings?->phone_code === '+52' ? 'selected' : '' }}>+52
                                            (Mexico)</option>
                                        <option value="+20"
                                            {{ $system_settings?->phone_code === '+20' ? 'selected' : '' }}>+20
                                            (Egypt)</option>
                                        <option value="+27"
                                            {{ $system_settings?->phone_code === '+27' ? 'selected' : '' }}>+27
                                            (South Africa)</option>
                                        <option value="+66"
                                            {{ $system_settings?->phone_code === '+66' ? 'selected' : '' }}>+66
                                            (Thailand)</option>
                                        <option value="+63"
                                            {{ $system_settings?->phone_code === '+63' ? 'selected' : '' }}>+63
                                            (Philippines)</option>
                                        <option value="+55"
                                            {{ $system_settings?->phone_code === '+55' ? 'selected' : '' }}>+55
                                            (Brazil)</option>
                                        <option value="+98"
                                            {{ $system_settings?->phone_code === '+98' ? 'selected' : '' }}>+98
                                            (Iran)</option>
                                        <option value="+90"
                                            {{ $system_settings?->phone_code === '+90' ? 'selected' : '' }}>+90
                                            (Turkey)</option>
                                        <option value="+82"
                                            {{ $system_settings?->phone_code === '+82' ? 'selected' : '' }}>+82
                                            (South Korea)</option>
                                        <option value="+34"
                                            {{ $system_settings?->phone_code === '+34' ? 'selected' : '' }}>+34
                                            (Spain)</option>
                                        <option value="+32"
                                            {{ $system_settings?->phone_code === '+32' ? 'selected' : '' }}>+32
                                            (Belgium)</option>
                                        <option value="+31"
                                            {{ $system_settings?->phone_code === '+31' ? 'selected' : '' }}>+31
                                            (Netherlands)</option>
                                        <option value="+47"
                                            {{ $system_settings?->phone_code === '+47' ? 'selected' : '' }}>+47
                                            (Norway)</option>
                                        <option value="+48"
                                            {{ $system_settings?->phone_code === '+48' ? 'selected' : '' }}>+48
                                            (Poland)</option>
                                        <option value="+41"
                                            {{ $system_settings?->phone_code === '+41' ? 'selected' : '' }}>+41
                                            (Switzerland)</option>
                                        <option value="+46"
                                            {{ $system_settings?->phone_code === '+46' ? 'selected' : '' }}>+46
                                            (Sweden)</option>
                                        <option value="+45"
                                            {{ $system_settings?->phone_code === '+45' ? 'selected' : '' }}>+45
                                            (Denmark)</option>
                                        <option value="+354"
                                            {{ $system_settings?->phone_code === '+354' ? 'selected' : '' }}>
                                            +354
                                            (Iceland)</option>
                                        <option value="+351"
                                            {{ $system_settings?->phone_code === '+351' ? 'selected' : '' }}>
                                            +351
                                            (Portugal)</option>
                                        <option value="+353"
                                            {{ $system_settings?->phone_code === '+353' ? 'selected' : '' }}>
                                            +353
                                            (Ireland)</option>
                                        <option value="+93"
                                            {{ $system_settings?->phone_code === '+93' ? 'selected' : '' }}>+93
                                            (Afghanistan)</option>
                                        <option value="+994"
                                            {{ $system_settings?->phone_code === '+994' ? 'selected' : '' }}>
                                            +994
                                            (Azerbaijan)</option>
                                        <option value="+1"
                                            {{ $system_settings?->phone_code === '+1' ? 'selected' : '' }}>+1
                                            (Bahrain)</option>
                                        <option value="+880"
                                            {{ $system_settings?->phone_code === '+880' ? 'selected' : '' }}>
                                            +880
                                            (Bangladesh)</option>
                                        <option value="+975"
                                            {{ $system_settings?->phone_code === '+975' ? 'selected' : '' }}>
                                            +975
                                            (Bhutan)</option>
                                        <option value="+855"
                                            {{ $system_settings?->phone_code === '+855' ? 'selected' : '' }}>
                                            +855
                                            (Cambodia)</option>
                                        <option value="+86"
                                            {{ $system_settings?->phone_code === '+86' ? 'selected' : '' }}>+86
                                            (China)</option>
                                        <option value="+357"
                                            {{ $system_settings?->phone_code === '+357' ? 'selected' : '' }}>
                                            +357
                                            (Cyprus)</option>
                                        <option value="+61"
                                            {{ $system_settings?->phone_code === '+61' ? 'selected' : '' }}>+61
                                            (Georgia)</option>
                                        <option value="+91"
                                            {{ $system_settings?->phone_code === '+91' ? 'selected' : '' }}>+91
                                            (India)</option>
                                        <option value="+62"
                                            {{ $system_settings?->phone_code === '+62' ? 'selected' : '' }}>+62
                                            (Indonesia)</option>
                                        <option value="+98"
                                            {{ $system_settings?->phone_code === '+98' ? 'selected' : '' }}>+98
                                            (Iran)</option>
                                        <option value="+81"
                                            {{ $system_settings?->phone_code === '+81' ? 'selected' : '' }}>+81
                                            (Japan)</option>
                                        <option value="+962"
                                            {{ $system_settings?->phone_code === '+962' ? 'selected' : '' }}>
                                            +962
                                            (Jordan)</option>
                                        <option value="+961"
                                            {{ $system_settings?->phone_code === '+961' ? 'selected' : '' }}>
                                            +961
                                            (Lebanon)</option>
                                        <option value="+960"
                                            {{ $system_settings?->phone_code === '+960' ? 'selected' : '' }}>
                                            +960
                                            (Maldives)</option>
                                        <option value="+60"
                                            {{ $system_settings?->phone_code === '+60' ? 'selected' : '' }}>+60
                                            (Malaysia)</option>
                                        <option value="+965"
                                            {{ $system_settings?->phone_code === '+965' ? 'selected' : '' }}>
                                            +965
                                            (Kuwait)</option>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" name="phone_number"
                                        value="{{ $system_settings->phone_number ?? '' }}"
                                        placeholder="Enter phone number">
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary px-4">Update Settings</button>
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
