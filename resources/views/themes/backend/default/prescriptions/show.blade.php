@extends('themes.backend.default.layouts.app')

@section('pre-content')
    @php
        $data = [
            [
                'title' => __('Prescriptions'),
                'url' => route('admin.prescriptions.index')
            ],
            [
                'title' => __('Prescription Details'),
                'url' => ''
            ]
        ];
    @endphp
    <x-backend.breadcrumbs title="{{ __('Prescriptions') }}" :links="$data" />
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    {{ __('Prescription Details') }}
                </div>
                <div class="float-end">
                    <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                    <a href="{{ route('admin.prescriptions.print', ['id' => $prescription->id ]) }}" class="btn btn-primary btn-sm">Print</a>
                </div>
            </div>
            <div class="card-body">

                <div class="mb-3 row">
                    <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>Patient Name:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $prescription->patient_name }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>Patient Birthday:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $prescription->patient_birthday }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="roles" class="col-md-4 col-form-label text-md-end text-start"><strong>Medicines:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="roles" class="col-md-4 col-form-label"></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>{{ __('Product Name') }}</th>
                                    <th>{{ __('Box') }}</th>
                                    <th>{{ __('Dosage') }}</th>
                                    <th>{{ __('Note') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($prescription->medicines as $medicine)
                                    <tr>
                                        <td>{{ $medicine->product->name }}</td>
                                        <td>{{ $medicine->box }}</td>
                                        <td>{{ $medicine->dosage }}</td>
                                        <td>{{ $medicine->note }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
