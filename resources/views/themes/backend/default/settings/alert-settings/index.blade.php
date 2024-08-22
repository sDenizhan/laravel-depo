@extends('themes.backend.default.layouts.app')

@section('pre-content')
    @php
        $data = [
            [
                'title' => __('Alert Settings'),
                'url' => route('admin.alert-settings.index')
            ],
            [
                'title' => __('Edit Alert Settings'),
                'url' => ''
            ]
        ];
    @endphp
    <x-backend.breadcrumbs title="{{ __('Alert Settings') }}" :links="$data" />
@endsection

@section('content')
    <form action="{{ route('admin.alert-settings.store') }}" method="POST">
        @csrf
        @method('POST')
        <div class="row">
            <!-- Email Settings -->
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="email_enabled" name="email_enabled" value="1" {{ old('email_enabled') ?? $settings['email_enabled'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="email_enabled">{{ __('Enable Email Notifications') }}</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="email_address" class="form-label">{{ __('Send to Email Address') }}</label>
                            <input type="email" class="form-control @error('email_address') is-invalid @enderror" id="email_address" name="email_address" value="{{ old('email_address') ?? $settings['email_address'] }}">
                            @error('email_address')
                            <span class="text-danger">{{ $errors->first('email_address') }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email_subject" class="form-label">{{ __('Email Subject') }}</label>
                            <input type="text" class="form-control @error('email_subject') is-invalid @enderror" id="email_subject" name="email_subject" value="{{ old('email_subject') ?? $settings['email_subject'] }}">
                            @error('email_subject')
                            <span class="text-danger">{{ $errors->first('email_subject') }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email_message" class="form-label">{{ __('Email Message') }}</label>
                            <textarea name="email_message" id="email_message" class="form-control @error('email_message') is-invalid @enderror">{{ old('email_message') ?? $settings['email_message'] }}</textarea>
                            @error('email_message')
                            <span class="text-danger">{{ $errors->first('email_message') }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- SMS Settings -->
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="sms_enabled" name="sms_enabled" value="1" {{ $settings['sms_enabled'] == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="sms_enabled">{{ __('Enable SMS Notifications') }}</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="sms_number" class="form-label">{{ __('Send to SMS Number') }}</label>
                            <input type="text" class="form-control @error('sms_number') is-invalid @enderror" id="sms_number" name="sms_number" value="{{ old('sms_number') ?? $settings['sms_number'] }}">
                            @error('sms_number')
                            <span class="text-danger">{{ $errors->first('sms_number') }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="sms_message" class="form-label">{{ __('SMS Message') }} <small>({{ __('Max 150 characters') }})</small></label>
                            <textarea name="sms_message" id="sms_message" class="form-control @error('sms_message') is-invalid @enderror" maxlength="150">{{ old('sms_message') ?? $settings['sms_message'] }}</textarea>
                            @error('sms_message')
                            <span class="text-danger">{{ $errors->first('sms_message') }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card-footer">
                    <button type="submit" class="btn btn-sm btn-primary">{{ __('Save Settings') }}</button>
                </div>
            </div>
        </div>
    </form>
@endsection
