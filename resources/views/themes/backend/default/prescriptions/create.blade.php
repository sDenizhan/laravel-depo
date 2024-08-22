@extends('themes.backend.default.layouts.app')

@section('pre-content')
    @php
        $data = [
            [
                'title' => __('Prescriptions'),
                'url' => route('admin.prescriptions.index')
            ],
            [
                'title' => __('Add New'),
                'url' => ''
            ]
        ];
    @endphp
    <x-backend.breadcrumbs title="{{ __('Prescriptions') }}" :links="$data" />
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.prescriptions.search') }}" method="GET">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="query form-label mb-2">{{ __('Medicine Search') }}</label>
                                    <div class="input-group">
                                        <input type="text" name="query" id="query" class="form-control" value="" placeholder="{{ __("Please Enter Product Name Or Product Barcode") }}">
                                        <button class="btn input-group-text btn-dark waves-effect waves-light search" type="button">{{ __('Search') }}</button>
                                    </div>
                                    <div class="search-result-box col-lg-12">
                                        <div class="bg-primary mb-2">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('admin.prescriptions.store') }}" method="post" id="prescriptionForm">
                    @csrf
                    @method('POST')
                    <div class="card-body">
                        <div class="row mt-2">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="doctor" class="form-label">{{ __('Doctor') }} <span class="required">*</span> </label>
                                    <select name="doctor_id" class="form-control">
                                        <option value="">{{ __('Select Doctor') }}</option>
                                        @foreach($doctors as $doctor)
                                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="hospital" class="form-label">{{ __('Hospital') }} <span class="required">*</span> </label>
                                    <select name="hospital_id" class="form-control">
                                        <option value="">{{ __('Select Hospital') }}</option>
                                        @foreach($hospitals as $hospital)
                                            <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="name" class="form-label">{{ __('Patient Name') }} <span class="required">*</span> </label>
                                    <input type="text" name="patient_name" class="form-control" value="{{ old('patient_name') }}">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="birthday" class="form-label">{{ __('Birthday') }}</label>
                                    <input type="text" name="patient_birthday" class="form-control" value="{{ old('patient_birthday') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <h3>{{ __('Medicines') }}</h3>
                            <div class="responsive-table-plugin">
                                <table class="table table-bordered" id="productList">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Medicine') }}</th>
                                            <th>{{ __('Box') }}</th>
                                            <th>{{ __('Dosage') }}</th>
                                            <th>{{ __('Note') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('error') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="result">

    </div>
@endsection

@push('styles')
    <link href="{{ asset('themes/backend/default/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('scripts')
    <script src="{{ asset('themes/backend/default/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function(){

            $(document).on('keyup', 'input#query', function(){
                var query = $(this).val();
                if(query.length > 2){
                    $.ajax({
                        url: "{{ route('admin.prescriptions.search') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            query: query
                        },
                        success: function(response){
                            if (response.status === 'success') {
                                var html = '<div class="list-group">';
                                $(response.products).each(function(index, product){
                                    html += '<a href="javascript:void(0)" class="list-group-item list-group-item-action add-to-cart" data-id="'+product.id+'">'+product.name+'</a>';
                                });
                                html += '</div>';

                                $('.search-result-box').find('div').empty().css({'z-index': 9999}).fadeIn().html(html);
                            }
                        }
                    });
                }
            });

            // Add to cart
            $(document).on('click', 'a.add-to-cart', function(){
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.prescriptions.select-medicine') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function(response){
                        if (response.status === 'success') {
                            var html = '<tr>';
                            html += '<td>'+response.product.name+'</td>';
                            html += '<td><input type="number" name="box['+response.product.id+']" class="form-control" value="1"></td>';
                            html += '<td><input type="text" name="dosage['+response.product.id+']" class="form-control" value="1x1"></td>';
                            html += '<td><input type="text" name="note['+response.product.id+']" class="form-control" value=""></td>';
                            html += '<td><a href="#" class="btn btn-danger remove-from-cart" data-id="'+response.product.id+'"><i class="   fas fa-trash-alt"></i></a></td>';
                            html += '</tr>';

                            $('#productList tbody').append(html);
                            $('.search-result-box').find('div').empty().fadeOut();
                        }
                    }
                });
            });

            // Remove from cart
            $(document).on('click', 'a.remove-from-cart', function(){
                var id = $(this).data('id');
                $(this).closest('tr').remove();
            });

            // Save prescription
            $(document).on('submit', 'form#prescriptionForm', function (e){
                e.preventDefault();
                var patient_name = $('input[name="patient_name"]').val();
                if (patient_name === '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Patient name is required!',
                    });
                    return false;
                }

                var data = $(this).serialize();
                $.post($(this).attr('action'), data, function(response){
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        });
                        $('form#prescriptionForm').trigger('reset');
                        $('#productList tbody').empty();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                        });
                    }
                });
            })
        });
    </script>
@endpush
