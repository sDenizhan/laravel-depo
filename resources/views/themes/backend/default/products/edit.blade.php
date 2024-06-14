@extends('themes.backend.default.layouts.app')

@section('pre-content')
    @php
        $data = [
            [
                'title' => __('Treatments'),
                'url' => route('admin.treatments.index')
            ],
            [
                'title' => __('Edit Treatment'),
                'url' => ''
            ]
        ];
    @endphp
    <x-backend.breadcrumbs title="{{ __('Treatments') }}" :links="$data" />
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form action="{{ route('admin.treatments.update', ['treatment' => $treatment->id]) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label for="name" class="col-md-2 col-form-label">Name</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $treatment->name ?? old('name') }}">
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="status" class="col-md-2 col-form-label">Status</label>
                            <div class="col-md-10">
                                <select name="status" id="status2" class="form-control @error('status') is-invalid @enderror">
                                    @foreach(\App\Enums\Status::toArray() as $key => $value)
                                        <option value="{{ $key }}" {{ old('status') || $treatment->status == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('status'))
                                    <span class="text-danger">{{ $errors->first('status') }}</span>
                                @endif
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
