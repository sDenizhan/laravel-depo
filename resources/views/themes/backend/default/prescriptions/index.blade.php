@extends('themes.backend.default.layouts.app')

@section('pre-content')
    @php
        $data = [
            [
                'title' => __('Prescriptions'),
                'url' => route('admin.prescriptions.index')
            ],
            [
                'title' => __('Index'),
                'url' => ''
            ]
        ];
    @endphp
    <x-backend.breadcrumbs title="{{ __('Prescriptions') }}" :links="$data" />
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-pills card-header-pills">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.prescriptions.create') }}">{{ __('Add New') }}</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>{{ __('Patient Name') }}</th>
                            <th>{{ __('Patient Birthday') }}</th>
                            @hasanyrole('Super Admin')
                                <th>{{ __('Manager') }}</th>
                            @endrole
                            <th>{{ __('Created At') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prescriptions as $row)
                            <tr>
                                <td>{{ $row->patient_name }}</td>
                                <td>{{ $row->patient_birthday }}</td>
                                @hasanyrole('Super Admin')
                                <td>{{ $row->user->name }}</td>
                                @endrole
                                <td>{{ $row->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>
                                    @can('show-prescription')
                                        <a href="{{ route('admin.prescriptions.show', ['prescription' => $row->id]) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.prescriptions.print', ['id' => $row->id]) }}" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-print"></i></a>
                                    @endcan

                                    @can('delete-prescription')
                                        <a href="{{ route('admin.prescriptions.destroy', ['prescription' => $row->id]) }}" class="btn btn-danger btn-sm remove_button"><i class="fas fa-trash-alt"></i></a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
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

 <link href="{{ asset('themes/backend/default/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
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
    <script src="{{ asset('themes/backend/default/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Datatables init -->
    <script src=" {{ asset('themes/backend/default/assets/js/pages/datatables.init.js') }}"></script>

    <script>
        $(document).ready(function(){
            //sweet alert remove button
            $(document).on('click', 'a.remove_button', function(e){
                e.preventDefault();
                var url = $(this).attr('href');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: !0,
                    confirmButtonColor: "#28bb4b",
                    cancelButtonColor: "#f34e4e",
                    confirmButtonText: "Yes, delete it!"
                }).then(function(e) {
                    if (e.value) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method : 'DELETE'
                            },
                            success: function(data){
                                if(data.success){
                                    Swal.fire({
                                        icon: "success",
                                        title: "Deleted!",
                                        text: 'the category has been deleted.',
                                    }).then(function(){
                                        location.reload();
                                    });
                                }else{
                                    Swal.fire({
                                        icon: "error",
                                        title: "Error!",
                                        text: 'the category has not been deleted.',
                                    });
                                }
                            }
                        });
                    }
                })
            });


        });
    </script>
@endpush
