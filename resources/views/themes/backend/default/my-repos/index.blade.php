@extends('themes.backend.default.layouts.app')

@section('pre-content')
    @php
        $data = [
            [
                'title' => __('My Inventory'),
                'url' => route('admin.my-repo.index')
            ],
            [
                'title' => __('Index'),
                'url' => ''
            ]
        ];
    @endphp
    <x-backend.breadcrumbs title="{{ __('My Inventory') }}" :links="$data" />
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills card-header-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('admin.my-repo.new-request') }}">{{ __('New Request') }}</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Quantity') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!is_null($products))
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->product->name }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>
                                        <a href="" class="btn btn-primary btn-sm">{{ __('Edit') }}</a>
                                        <a href="" class="btn btn-danger btn-sm">{{ __('Delete') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">{{ __('No data found') }}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- third party css -->
    <link href="{{ asset('themes/backend/default/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('themes/backend/default/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('themes/backend/default/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('themes/backend/default/assets/libs/datatables.net-select-bs5/css//select.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->
@endpush

@push('scripts')
    <!-- third party js -->
    <script src=" {{ asset('themes/backend/default/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src=" {{ asset('themes/backend/default/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src=" {{ asset('themes/backend/default/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src=" {{ asset('themes/backend/default/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src=" {{ asset('themes/backend/default/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src=" {{ asset('themes/backend/default/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src=" {{ asset('themes/backend/default/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src=" {{ asset('themes/backend/default/assets/libs/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src=" {{ asset('themes/backend/default/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src=" {{ asset('themes/backend/default/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src=" {{ asset('themes/backend/default/assets/libs/datatables.net-select/js/dataTables.select.min.js') }}"></script>
    <script src=" {{ asset('themes/backend/default/assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src=" {{ asset('themes/backend/default/assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <!-- third party js ends -->

    <!-- Datatables init -->
    <script src=" {{ asset('themes/backend/default/assets/js/pages/datatables.init.js') }}"></script>
@endpush
