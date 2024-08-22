@extends('themes.backend.default.layouts.app')

@section('pre-content')
    @php
        $data = [
            [
                'title' => __('Doctors'),
                'url' => route('admin.doctors.index')
            ],
            [
                'title' => __('Edit'),
                'url' => ''
            ]
        ];
    @endphp
    <x-backend.breadcrumbs title="{{ __('Doctors') }}" :links="$data" />
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form action="{{ route('admin.doctors.update', ['doctor' => $doctor->id]) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Doctor Name') }}</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="{{ __('Name') }}" value="{{ old('name') ?? $doctor->name }}">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="hospital_id" class="form-label">{{ __('Hospital') }}</label>
                                    <select name="hospital_id" id="hospital_id" class="form-control">
                                        @foreach($hospitals as $hospital)
                                            <option value="{{ $hospital->id }}" {{ $doctor->hospital_id == $hospital->id ? 'selected="selected"' : '' }}>{{ $hospital->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('hospital_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-sm btn-primary">{{ __('Update') }}</button>
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
