@extends('themes.backend.default.layouts.app')

@section('pre-content')
    @php
        $data = [
            [
                'title' => __('Hospitals'),
                'url' => route('admin.hospitals.index')
            ],
            [
                'title' => __('Edit Hospital'),
                'url' => ''
            ]
        ];
    @endphp
    <x-backend.breadcrumbs title="{{ __('Edit Hospital') }}" :links="$data" />
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form action="{{ route('admin.hospitals.update', ['hospital' => $hospital->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Hospital Name') }}</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="{{ __('Name') }}" value="{{ old('name') ?? $hospital->name }}">
                            @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">{{ __('Address') }}</label>
                            <textarea name="address" id="address" class="form-control" placeholder="{{ __('Address') }}">{{ old('address') ?? $hospital->address }}</textarea>
                            @error('address')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="statusx" class="form-label">{{ __('Status') }}</label>
                            <select name="status" id="statusx" class="form-control">
                                @php
                                    $status = App\Enums\Status::toArray();
                                    foreach ($status as $key => $value) {
                                        $selected = old('status') == $key ? 'selected' : '';
                                        echo '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
                                    }
                                @endphp
                            </select>
                            @error('status')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="doctors" class="form-label">{{ __('Doctors') }}</label>
                            <select name="doctors[]" id="doctors" class="form-control select2" multiple>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ in_array($doctor->id, $selectedDoctors->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $doctor->name }}</option>
                                @endforeach
                            </select>
                            @error('doctors')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
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
    <script src="{{ asset('themes/backend/default/assets/libs/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
