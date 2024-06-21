@extends('themes.backend.default.layouts.app')

@section('pre-content')
    @php
        $data = [
            [
                'title' => __('Repositories'),
                'url' => route('admin.repos.index')
            ],
            [
                'title' => __('Index'),
                'url' => ''
            ]
        ];
    @endphp
    <x-backend.breadcrumbs title="{{ __('Repositories') }}" :links="$data" />
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="mt-2 mb-1 text-muted">{{ __('Assigned To') }}</p>
                            <div class="d-flex align-items-start">
                                <div class="w-100">
                                    <h5 class="mt-1 font-size-14">
                                        {{ $repo->user->name }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <p class="mt-2 mb-1 text-muted">{{ __('Repository Name') }}</p>
                            <div class="d-flex align-items-start">
                                <i class="mdi mdi-briefcase-check-outline font-18 text-success me-1"></i>
                                <div class="w-100">
                                    <h5 class="mt-1 font-size-14">
                                        {{ $repo->name }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <p class="mt-2 mb-1 text-muted">{{ __('Is Main') }}</p>
                            <div class="d-flex align-items-start">
                                <i class="mdi mdi-briefcase-check-outline font-18 text-success me-1"></i>
                                <div class="w-100">
                                    <h5 class="mt-1 font-size-14">
                                        {{ $repo->is_main ? __('Yes') : __('No') }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <p class="mt-2 mb-1 text-muted">{{ __('Min. Alert Count') }}</p>
                            <div class="d-flex align-items-start">
                                <i class="mdi mdi-briefcase-check-outline font-18 text-success me-1"></i>
                                <div class="w-100">
                                    <h5 class="mt-1 font-size-14">
                                        {{ $repo->min_alert }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-centered table-nowrap">
                            <thead>
                            <tr>
                                <th scope="col" style="width: 70px;">#</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Category') }}</th>
                                <th scope="col">{{ __('Quantity') }}</th>
                                <th scope="col">{{ __('Price') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($repo->products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>{{ $product->pivot->quantity }}</td>
                                    <td>{{ join(' ', [$product->price, \App\Enums\Currency::from($product->currency)->name]) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __("Repository Logs") }}</h4>
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap table-hover mb-0">
                                <thead>
                                <tr>
                                    <th scope="col">{{ __("ID") }}</th>
                                    <th scope="col">{{ __("User") }}</th>
                                    <th scope="col">{{ __("Action") }}</th>
                                    <th scope="col">{{ __("Created At") }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($logs as $log)
                                    <tr>
                                        <td>{{ $log->id }}</td>
                                        <td>{{ $log->user->name }}</td>
                                        <td>{{ $log->action }}</td>
                                        <td>{{ $log->created_at->diffForHumans() }}</td>
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

@endsection
