@extends('themes.backend.default.layouts.app')

@section('pre-content')
    <x-backend.breadcrumbs title="{{ __('Dashboard') }}" />
@endsection

@section('content')

    <div class="row">
        <div class="col-xl-6 col-md-12">
            <!-- Portlet card -->
            <div class="card">
                <div class="card-body">
                    <div class="card-widgets">
                        <a href="javascript: void(0);" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                        <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false" aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                        <a href="javascript: void(0);" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                    </div>
                    <h4 class="header-title mb-0">{{ __('Number of Products Discharged by Hospital / Annual') }}</h4>

                    <div id="cardCollpase1" class="collapse show">
                        <div class="text-center pt-3">
                            <div id="chart1" data-colors="#00acc1,#f1556c"></div>
                        </div>
                    </div> <!-- collapsed end -->
                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col-->

        <div class="col-xl-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-widgets">
                        <a href="javascript: void(0);" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                        <a data-bs-toggle="collapse" href="#cardCollpase2" role="button" aria-expanded="false" aria-controls="cardCollpase2"><i class="mdi mdi-minus"></i></a>
                        <a href="javascript: void(0);" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                    </div>
                    <h4 class="header-title mb-0">{{ __('Total Inventory in Main Warehouse / Category') }}</h4>

                    <div id="cardCollpase2" class="collapse show">
                        <div class="text-center pt-3">
                            <div id="chart2" data-colors="#00acc1"></div>
                        </div>
                    </div> <!-- collapsed end -->
                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div>
    <!-- end row -->

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

@push('scripts')
    <!-- apexcharts -->
    <script src="{{ asset('themes/backend/default/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="https://apexcharts.com/samples/assets/irregular-data-series.js"></script>
    <script src="https://apexcharts.com/samples/assets/ohlc.js"></script>

    <script>
        $(document).ready(function (){
            var options = {
                series: [44, 55, 13, 43, 22],
                chart: {
                    width: 510,
                    type: 'pie',
                },
                labels: ['Team A', 'Team B', 'Team C', 'Team D', 'Team E'],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector("#chart2"), options);
            chart.render();


            var options = {
                series: [{
                    name: 'PRODUCT A',
                    data: [44, 55, 41, 67, 22, 43]
                }, {
                    name: 'PRODUCT B',
                    data: [13, 23, 20, 8, 13, 27]
                }, {
                    name: 'PRODUCT C',
                    data: [11, 17, 15, 15, 21, 14]
                }, {
                    name: 'PRODUCT D',
                    data: [21, 7, 25, 13, 22, 8]
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: true
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        legend: {
                            position: 'bottom',
                            offsetX: -10,
                            offsetY: 0
                        }
                    }
                }],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 10,
                        borderRadiusApplication: 'end', // 'around', 'end'
                        borderRadiusWhenStacked: 'last', // 'all', 'last'
                        dataLabels: {
                            total: {
                                enabled: true,
                                style: {
                                    fontSize: '13px',
                                    fontWeight: 900
                                }
                            }
                        }
                    },
                },
                xaxis: {
                    type: 'string',
                    categories: ['hospital repo 1', 'hospital repo 2', 'hospital repo 3', 'hospital repo 4', 'hospital repo 5', 'hospital repo 6'],
                },
                legend: {
                    position: 'right',
                    offsetY: 40
                },
                fill: {
                    opacity: 1
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart1"), options);
            chart.render();
        });
    </script>

@endpush
