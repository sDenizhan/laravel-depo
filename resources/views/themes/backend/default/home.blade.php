@extends('themes.backend.default.layouts.app')

@section('pre-content')
    <x-backend.breadcrumbs title="{{ __('Dashboard') }}" />
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">{{ __('Last Activities') }}</h4>

                    @if($logs)
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap table-hover mb-0">
                                <thead>
                                <tr>
                                    <th class="border-top-0">{{ __('Repo Name') }}</th>
                                    <th class="border-top-0">{{ __('Type') }}</th>
                                    <th class="border-top-0">{{ __('Created By') }}</th>
                                    <th class="border-top-0">{{ __('Created At') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($logs as $log)
                                        <tr>
                                            <td>
                                                {{ $log->repo->name ?? '' }}
                                            </td>
                                            <td>{{ $log->action == 'in' ? 'IN' : 'OUT' }}</td>
                                            <td>{{ $log->user->name }}</td>
                                            <td>{{ $log->created_at }}</td>
                                            <td>
                                                <span class="badge badge-{{ $log->status == 'success' ? 'success' : 'danger' }} font-size-12">{{ $log->status }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div> <!-- end card-->
        </div> <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">{{ __('Recent Prescriptions') }}</h4>

                    @if($prescriptions)
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap table-hover mb-0">
                                <thead>
                                <tr>
                                    <th class="border-top-0">{{ __('Patient Name') }}</th>
                                    <th class="border-top-0">{{ __('Doctor Name') }}</th>
                                    <th class="border-top-0">{{ __('Created Date') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($prescriptions as $pres)
                                    <tr>
                                        <td>
                                            {{ $pres->patient_name }}
                                        </td>
                                        <td>{{ $pres->doctor->name ?? '' }}</td>
                                        <td>{{ $pres->created_at }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div> <!-- end table-responsive -->
                    @endif
                </div>
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div>
@endsection
