@extends('backend.master')

@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/datatable/css/datatables.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
    <style>

    </style>
@endpush

@section('title', 'System Settings')

@section('content')
    <div>
        <div class="card ">
            <div class="card-header">
                <h3 class="mb-0">System Settings</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('system.update') }}" enctype="multipart/form-data">
                    @csrf

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
                        <div class="row align-items-center mb-3">
                            <label class="col-sm-4 col-form-label">{{ $label }}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="{{ $name }}"
                                    value="{{ $system_settings->$name ?? '' }}"
                                    placeholder="Enter {{ strtolower($label) }}">
                                @error($name)
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endforeach

                    <!-- Phone -->
                    <div class="row align-items-center mb-3">
                        <label class="col-sm-4 col-form-label">Phone</label>
                        <div class="col-sm-4">
                            <select class="form-select" name="phone_code">
                                <!-- Options here -->
                                <option value="+1" {{ $system_settings?->phone_code === '+1' ? 'selected' : '' }}>+1
                                    (USA)
                                </option>
                                <option value="+1" {{ $system_settings?->phone_code === '+1' ? 'selected' : '' }}>+1
                                    (Canada)</option>
                                <option value="+44" {{ $system_settings?->phone_code === '+44' ? 'selected' : '' }}>+44
                                    (United Kingdom)</option>
                                <option value="+91" {{ $system_settings?->phone_code === '+91' ? 'selected' : '' }}>+91
                                    (India)</option>
                                <option value="+61" {{ $system_settings?->phone_code === '+61' ? 'selected' : '' }}>+61
                                    (Australia)</option>
                                <option value="+81" {{ $system_settings?->phone_code === '+81' ? 'selected' : '' }}>+81
                                    (Japan)</option>
                                <option value="+49" {{ $system_settings?->phone_code === '+49' ? 'selected' : '' }}>+49
                                    (Germany)</option>
                                <option value="+33" {{ $system_settings?->phone_code === '+33' ? 'selected' : '' }}>+33
                                    (France)</option>
                                <option value="+34" {{ $system_settings?->phone_code === '+34' ? 'selected' : '' }}>+34
                                    (Spain)</option>
                                <option value="+39" {{ $system_settings?->phone_code === '+39' ? 'selected' : '' }}>+39
                                    (Italy)</option>
                                <option value="+55" {{ $system_settings?->phone_code === '+55' ? 'selected' : '' }}>+55
                                    (Brazil)</option>
                                <option value="+7" {{ $system_settings?->phone_code === '+7' ? 'selected' : '' }}>+7
                                    (Russia)</option>
                                <option value="+86" {{ $system_settings?->phone_code === '+86' ? 'selected' : '' }}>+86
                                    (China)</option>
                                <option value="+91" {{ $system_settings?->phone_code === '+91' ? 'selected' : '' }}>+91
                                    (India)</option>
                                <option value="+62" {{ $system_settings?->phone_code === '+62' ? 'selected' : '' }}>+62
                                    (Indonesia)</option>
                                <option value="+971" {{ $system_settings?->phone_code === '+971' ? 'selected' : '' }}>
                                    +971
                                    (United Arab Emirates)</option>
                                <option value="+52" {{ $system_settings?->phone_code === '+52' ? 'selected' : '' }}>+52
                                    (Mexico)</option>
                                <option value="+20" {{ $system_settings?->phone_code === '+20' ? 'selected' : '' }}>+20
                                    (Egypt)</option>
                                <option value="+27" {{ $system_settings?->phone_code === '+27' ? 'selected' : '' }}>+27
                                    (South Africa)</option>
                                <option value="+66" {{ $system_settings?->phone_code === '+66' ? 'selected' : '' }}>+66
                                    (Thailand)</option>
                                <option value="+63" {{ $system_settings?->phone_code === '+63' ? 'selected' : '' }}>+63
                                    (Philippines)</option>
                                <option value="+55" {{ $system_settings?->phone_code === '+55' ? 'selected' : '' }}>+55
                                    (Brazil)</option>
                                <option value="+98" {{ $system_settings?->phone_code === '+98' ? 'selected' : '' }}>+98
                                    (Iran)</option>
                                <option value="+90" {{ $system_settings?->phone_code === '+90' ? 'selected' : '' }}>+90
                                    (Turkey)</option>
                                <option value="+82" {{ $system_settings?->phone_code === '+82' ? 'selected' : '' }}>+82
                                    (South Korea)</option>
                                <option value="+34" {{ $system_settings?->phone_code === '+34' ? 'selected' : '' }}>+34
                                    (Spain)</option>
                                <option value="+32" {{ $system_settings?->phone_code === '+32' ? 'selected' : '' }}>+32
                                    (Belgium)</option>
                                <option value="+31" {{ $system_settings?->phone_code === '+31' ? 'selected' : '' }}>+31
                                    (Netherlands)</option>
                                <option value="+47" {{ $system_settings?->phone_code === '+47' ? 'selected' : '' }}>+47
                                    (Norway)</option>
                                <option value="+48" {{ $system_settings?->phone_code === '+48' ? 'selected' : '' }}>+48
                                    (Poland)</option>
                                <option value="+41" {{ $system_settings?->phone_code === '+41' ? 'selected' : '' }}>+41
                                    (Switzerland)</option>
                                <option value="+46" {{ $system_settings?->phone_code === '+46' ? 'selected' : '' }}>+46
                                    (Sweden)</option>
                                <option value="+45" {{ $system_settings?->phone_code === '+45' ? 'selected' : '' }}>+45
                                    (Denmark)</option>
                                <option value="+354" {{ $system_settings?->phone_code === '+354' ? 'selected' : '' }}>
                                    +354
                                    (Iceland)</option>
                                <option value="+351" {{ $system_settings?->phone_code === '+351' ? 'selected' : '' }}>
                                    +351
                                    (Portugal)</option>
                                <option value="+353" {{ $system_settings?->phone_code === '+353' ? 'selected' : '' }}>
                                    +353
                                    (Ireland)</option>
                                <option value="+93" {{ $system_settings?->phone_code === '+93' ? 'selected' : '' }}>+93
                                    (Afghanistan)</option>
                                <option value="+994" {{ $system_settings?->phone_code === '+994' ? 'selected' : '' }}>
                                    +994
                                    (Azerbaijan)</option>
                                <option value="+1" {{ $system_settings?->phone_code === '+1' ? 'selected' : '' }}>+1
                                    (Bahrain)</option>
                                <option value="+880" {{ $system_settings?->phone_code === '+880' ? 'selected' : '' }}>
                                    +880
                                    (Bangladesh)</option>
                                <option value="+975" {{ $system_settings?->phone_code === '+975' ? 'selected' : '' }}>
                                    +975
                                    (Bhutan)</option>
                                <option value="+855" {{ $system_settings?->phone_code === '+855' ? 'selected' : '' }}>
                                    +855
                                    (Cambodia)</option>
                                <option value="+86" {{ $system_settings?->phone_code === '+86' ? 'selected' : '' }}>+86
                                    (China)</option>
                                <option value="+357" {{ $system_settings?->phone_code === '+357' ? 'selected' : '' }}>
                                    +357
                                    (Cyprus)</option>
                                <option value="+61" {{ $system_settings?->phone_code === '+61' ? 'selected' : '' }}>+61
                                    (Georgia)</option>
                                <option value="+91" {{ $system_settings?->phone_code === '+91' ? 'selected' : '' }}>+91
                                    (India)</option>
                                <option value="+62" {{ $system_settings?->phone_code === '+62' ? 'selected' : '' }}>+62
                                    (Indonesia)</option>
                                <option value="+98" {{ $system_settings?->phone_code === '+98' ? 'selected' : '' }}>+98
                                    (Iran)</option>
                                <option value="+81" {{ $system_settings?->phone_code === '+81' ? 'selected' : '' }}>+81
                                    (Japan)</option>
                                <option value="+962" {{ $system_settings?->phone_code === '+962' ? 'selected' : '' }}>
                                    +962
                                    (Jordan)</option>
                                <option value="+961" {{ $system_settings?->phone_code === '+961' ? 'selected' : '' }}>
                                    +961
                                    (Lebanon)</option>
                                <option value="+960" {{ $system_settings?->phone_code === '+960' ? 'selected' : '' }}>
                                    +960
                                    (Maldives)</option>
                                <option value="+60" {{ $system_settings?->phone_code === '+60' ? 'selected' : '' }}>+60
                                    (Malaysia)</option>
                                <option value="+965" {{ $system_settings?->phone_code === '+965' ? 'selected' : '' }}>
                                    +965
                                    (Kuwait)</option>
                                <!-- Add more codes as needed -->
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="phone_number"
                                value="{{ $system_settings->phone_number ?? '' }}" placeholder="Enter phone number">
                        </div>
                    </div>

                    <!-- Logo -->
                    <div class="row align-items-center mb-3">
                        <label class="col-sm-4 col-form-label">Logo</label>
                        <div class="col-sm-8">
                            <input type="file" name="logo" class="form-control dropify"
                                @if (!empty($system_settings->logo)) data-default-file="{{ asset($system_settings->logo) }}" @endif>
                            @error('logo')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Favicon -->
                    <div class="row align-items-center mb-4">
                        <label class="col-sm-4 col-form-label">Favicon</label>
                        <div class="col-sm-8">
                            <input type="file" name="favicon" class="form-control dropify"
                                @if (!empty($system_settings->favicon)) data-default-file="{{ asset($system_settings->favicon) }}" @endif>
                            @error('favicon')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary px-4">Update</button>
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
