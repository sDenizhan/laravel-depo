@extends('themes.backend.default.layouts.app')

@section('pre-content')
    @php
        $data = [
            [
                'title' => __('Repositories'),
                'url' => route('admin.repos.index')
            ],
            [
                'title' => __('Add New'),
                'url' => ''
            ]
        ];
    @endphp
    <x-backend.breadcrumbs title="{{ __('Repositories') }}" :links="$data" />
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <form action="{{ route('admin.repos.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Repo Name') }}</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="{{ __('Name') }}" value="{{ old('name') }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="is_main" class="form-label">{{ __('Is Main Repo?') }}</label>
                                <select name="is_main" id="is_main" class="form-control">
                                    <option value="1" {{ old('is_main') == 1 ? 'selected' : '' }}>{{ __('Yes') }}</option>
                                    <option value="0" {{ old('is_main') == 0 ? 'selected' : '' }}>{{ __('No') }}</option>
                                </select>
                                @error('is_main')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="min_alert" class="form-label">{{ __('Min. Alert Count') }}</label>
                                <input type="text" name="min_alert" id="min_alert" class="form-control" placeholder="{{ __('Min. Alert Count') }}" value="{{ old('min_alert') }}">
                                @error('min_alert')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="user_id" class="form-label">{{ __('Manager') }}</label>
                                <select name="user_id" id="user_id" class="form-control">
                                    @php
                                    if ( $users->isNotEmpty() ) {
                                        foreach ($users as $user) {
                                            echo '<option value="'. $user->id .'">'. join(' - ', [$user->name, $user->getRoleNames()->first()]) .'</option>';
                                        }
                                    }
                                    @endphp
                                </select>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="is_hospital" class="form-label">{{ __('Hospital ?') }}</label>
                                <select name="is_hospital" id="is_hospital" class="form-control">
                                    <option value="1" {{ old('is_hospital') == 1 ? 'selected' : '' }}>{{ __('Yes') }}</option>
                                    <option value="0" {{ old('is_hospital') == 0 ? 'selected' : '' }}>{{ __('No') }}</option>
                                </select>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-sm btn-primary">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('styles')
    <link rel="stylesheet" href="{{ asset('themes/backend/default/assets/libs/multiselect/css/multi-select.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/backend/default/assets/libs/select2/css/select2.min.css') }}">
@endpush

@push('scripts')
@endpush
