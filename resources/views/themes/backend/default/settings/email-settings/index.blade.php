@extends('themes.backend.default.layouts.app')

@section('pre-content')
    @php
        $data = [
            [
                'title' => __('SMTP Settings'),
                'url' => route('admin.email-settings.index')
            ],
            [
                'title' => __('Edit Email Settings'),
                'url' => ''
            ]
        ];
    @endphp
    <x-backend.breadcrumbs title="{{ __('Email Settings') }}" :links="$data" />
@endsection

@section('content')
    <form action="{{ route('admin.email-settings.store') }}" method="POST">
        @csrf
        @method('POST')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <!-- SMTP Host -->
                            <div class="col-12 col-lg-6">
                                <div class="mb-3">
                                    <label for="smtp_host" class="form-label">{{ __('SMTP Host') }}<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('smtp_host') is-invalid @enderror" id="smtp_host" name="smtp_host" value="{{ old('smtp_host') ?? $settings['smtp_host'] }}">
                                    @error('smtp_host')
                                    <span class="text-danger">{{ $errors->first('smtp_host') }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- SMTP Port -->
                            <div class="col-12 col-lg-6">
                                <div class="mb-3">
                                    <label for="smtp_port" class="form-label">{{ __('SMTP Port') }}<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('smtp_port') is-invalid @enderror" id="smtp_port" name="smtp_port" value="{{ old('smtp_port') ?? $settings['smtp_port']}}">
                                    @error('smtp_port')
                                    <span class="text-danger">{{ $errors->first('smtp_port') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- SMTP Username -->
                            <div class="col-12 col-lg-6">
                                <div class="mb-3">
                                    <label for="smtp_username" class="form-label">{{ __('SMTP Username') }}<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('smtp_username') is-invalid @enderror" id="smtp_username" name="smtp_username" value="{{ old('smtp_username') ?? $settings['smtp_username'] }}">
                                    @error('smtp_username')
                                    <span class="text-danger">{{ $errors->first('smtp_username') }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- SMTP Password -->
                            <div class="col-12 col-lg-6">
                                <div class="mb-3">
                                    <label for="smtp_password" class="form-label">{{ __('SMTP Password') }}<span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('smtp_password') is-invalid @enderror" id="smtp_password" name="smtp_password" value="{{ old('smtp_password') ?? $settings['smtp_password'] }}">
                                    @error('smtp_password')
                                    <span class="text-danger">{{ $errors->first('smtp_password') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Encryption -->
                            <div class="col-12 col-lg-6">
                                <div class="mb-3">
                                    <label for="smtp_encryption" class="form-label">{{ __('Encryption Type') }}<span class="text-danger">*</span></label>
                                    <select class="form-control @error('smtp_encryption') is-invalid @enderror" id="smtp_encryption" name="smtp_encryption">
                                        <option value="tls" {{ old('smtp_encryption') ?? $settings['smtp_encryption'] == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ old('smtp_encryption') ?? $settings['smtp_encryption'] == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="none" {{ old('smtp_encryption') ?? $settings['smtp_encryption'] == 'none' ? 'selected' : '' }}>None</option>
                                    </select>
                                    @error('smtp_encryption')
                                    <span class="text-danger">{{ $errors->first('smtp_encryption') }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- From Email -->
                            <div class="col-12 col-lg-6">
                                <div class="mb-3">
                                    <label for="smtp_from_email" class="form-label">{{ __('From Email Address') }}<span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('smtp_from_email') is-invalid @enderror" id="smtp_from_email" name="smtp_from_email" value="{{ old('smtp_from_email') ?? $settings['smtp_from_email'] }}">
                                    @error('smtp_from_email')
                                    <span class="text-danger">{{ $errors->first('smtp_from_email') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- From Name -->
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="smtp_from_name" class="form-label">{{ __('From Name') }}<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('smtp_from_name') is-invalid @enderror" id="smtp_from_name" name="smtp_from_name" value="{{ old('smtp_from_name') ?? $settings['smtp_from_name'] }}">
                                    @error('smtp_from_name')
                                    <span class="text-danger">{{ $errors->first('smtp_from_name') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-sm btn-primary">{{ __('Save Settings') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
