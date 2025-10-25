@extends('backend.master')

@section('title', 'Active Directory Settings')

@push('style')
<style>
    body { background-color: #f7f8fa; }
    .ad-card { border-radius: 12px; box-shadow: 0 3px 15px rgba(0,0,0,0.08); padding: 20px; }
    .ad-card .card-header { background: linear-gradient(90deg,#343a40,#495057); color:#fff; font-weight:bold; text-align:center; border-radius:12px 12px 0 0; font-size:1rem; padding:10px 0; }
    .form-label { font-weight: 500; }
    .btn-submit { border-radius:8px; font-size:1rem; padding:0.5rem 1rem; }
</style>
@endpush

@section('content')
<div class="app-content content">
    <div class="row justify-content-center mt-4">
        <div class="col-lg-12">
            <div class="card ad-card">
                <div class="card-header">
                   <h4>Active Directory Settings</h4>
                </div>

                <div class="card-body">
                    {{-- @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif --}}

                    <form action="{{ route('directory.update') }}" method="POST">
                        @csrf

                        @php
                            $ldapConfig = config('ldap.connections.default');
                            $ldapConnection = config('ldap.default');
                            $loggingEnabled = config('ldap.logging.enabled') ?? 'false';

                            $ldapKeys = [
                                'LDAP_CONNECTION' => 'top_level',
                                'LDAP_HOST'       => 'hosts',
                                'Alt_LDAP_HOST'   => 'hosts',
                                'LDAP_USERNAME'   => 'username',
                                'LDAP_PASSWORD'   => 'password',
                                'LDAP_BASE_DN'    => 'base_dn',
                                'LDAP_PORT'       => 'port',
                                'LDAP_SSL'        => 'use_ssl',
                                'LDAP_TLS'        => 'use_tls',
                                'LDAP_TIMEOUT'    => 'timeout',
                                'LDAP_LOGGING'    => 'logging',
                            ];
                        @endphp

                        @foreach($ldapKeys as $envKey => $configKey)
                            @php
                                if ($configKey === 'top_level') {
                                    $value = $ldapConnection;
                                } elseif ($configKey === 'hosts') {
                                    $value = $ldapConfig['hosts'][0] ?? '';
                                } elseif ($configKey === 'logging') {
                                    $value = $loggingEnabled;
                                } else {
                                    $value = $ldapConfig[$configKey] ?? '';
                                }
                            @endphp

                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-form-label">{{ str_replace('_',' ',$envKey) }}</label>
                                <div class="col-sm-8">
                                    @if(str_contains($envKey,'PASSWORD'))
                                        <input type="password" name="{{ $envKey }}" class="form-control" value="{{ $value }}" placeholder="Enter {{ strtolower(str_replace('_',' ',$envKey)) }}">
                                    @elseif(in_array($envKey,['LDAP_SSL','LDAP_TLS','LDAP_LOGGING']))
                                        <select name="{{ $envKey }}" class="form-control">
                                            <option value="true" {{ $value === true || $value === 'true' ? 'selected' : '' }}>True</option>
                                            <option value="false" {{ $value === false || $value === 'false' ? 'selected' : '' }}>False</option>
                                        </select>
                                    @else
                                        <input type="text" name="{{ $envKey }}" class="form-control" value="{{ $value }}" required>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary btn-submit">Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
