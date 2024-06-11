@extends('themes.backend.default.layouts.app')

@section('pre-content')
    @php
        $data = [
            [
                'title' => __('Hospitals'),
                'url' => route('admin.hospitals.index')
            ],
            [
                'title' => __('Show Hospital'),
                'url' => ''
            ]
        ];
    @endphp
    <x-backend.breadcrumbs title="{{ __('Show Hospital') }}" :links="$data" />
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="float-start">
                        {{ __('Hospital Information') }}
                    </div>
                    <div class="float-end">
                        <a href="{{ route('admin.hospitals.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                    </div>
                </div>
                <div class="card-body">

                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>Name:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $hospital->name }}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-form-label text-md-end text-start"><strong>Address:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $hospital->address }}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="roles" class="col-md-4 col-form-label text-md-end text-start"><strong>Doctors:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            @forelse ($doctors as $doctor)
                                @if( $doctor->hasRole('Doctor') )
                                    <span class="badge bg-primary">{{ $doctor->name }}</span>
                                @endif
                            @empty
                            @endforelse
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="roles" class="col-md-4 col-form-label text-md-end text-start"><strong>Anasthestics:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            @forelse ($doctors as $doctor)
                                @if( $doctor->hasRole('Anaesthetist') )
                                    <span class="badge bg-primary">{{ $doctor->name }}</span>
                                @endif
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
