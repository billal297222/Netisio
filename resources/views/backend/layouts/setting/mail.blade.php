@extends('backend.master')

@section('title', 'Mail Settings')

@push('style')
<style>
    body {
        background-color: #f7f8fa;
    }

    .mail-card {
        border-radius: 12px;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        padding: 20px;
    }

    .mail-card .card-header {
        background: linear-gradient(90deg, #343a40, #495057);
        color: #fff;
        font-weight: bold;
        text-align: center;
        border-radius: 12px 12px 0 0;
        font-size: 1rem;
        padding: 10px 0;
    }

    .form-label {
        font-weight: 500;
    }

    .btn-submit {
        border-radius: 8px;
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }
</style>
@endpush

@section('content')
<div class="app-content content">
    <div class="row justify-content-center mt-4">
        <div class="col-lg-12">

            <!-- Single Card -->
            <div class="card mail-card">
                <div class="card-header">
                   <h4> Mail Settings</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('mail.update') }}" method="POST">
                        @csrf

                        <!-- MAIL MAILER -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-4 col-form-label">MAIL MAILER</label>
                            <div class="col-sm-8">
                                <input type="text" name="mail_mailer" class="form-control"
                                       value="{{ config('mail.default') }}" required>
                            </div>
                        </div>

                        <!-- MAIL HOST -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-4 col-form-label">MAIL HOST</label>
                            <div class="col-sm-8">
                                <input type="text" name="mail_host" class="form-control"
                                       value="{{ config('mail.mailers.smtp.host') }}" required>
                            </div>
                        </div>

                        <!-- MAIL PORT -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-4 col-form-label">MAIL PORT</label>
                            <div class="col-sm-8">
                                <input type="text" name="mail_port" class="form-control"
                                       value="{{ config('mail.mailers.smtp.port') }}" required>
                            </div>
                        </div>

                        <!-- MAIL USERNAME -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-4 col-form-label">MAIL USERNAME</label>
                            <div class="col-sm-8">
                                <input type="text" name="mail_username" class="form-control"
                                       value="{{ config('mail.mailers.smtp.username') }}" required>
                            </div>
                        </div>

                        <!-- MAIL PASSWORD -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-4 col-form-label">MAIL PASSWORD</label>
                            <div class="col-sm-8">
                                <input type="password" name="mail_password" class="form-control"
                                       value="{{ config('mail.mailers.smtp.password') }}" 
                                       placeholder="Enter mail password">
                            </div>
                        </div>

                        <!-- MAIL ENCRYPTION -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-4 col-form-label">MAIL ENCRYPTION</label>
                            <div class="col-sm-8">
                                <input type="text" name="mail_encryption" class="form-control"
                                       value="{{ config('mail.mailers.smtp.encryption') }}" required>
                            </div>
                        </div>

                        <!-- MAIL FROM ADDRESS -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-4 col-form-label">MAIL FROM ADDRESS</label>
                            <div class="col-sm-8">
                                <input type="email" name="mail_from_address" class="form-control"
                                       value="{{ config('mail.from.address') }}" required>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary btn-submit">Save Settings</button>
                        </div>

                    </form>
                </div>
            </div>
            <!-- End Single Card -->

        </div>
    </div>
</div>
@endsection
