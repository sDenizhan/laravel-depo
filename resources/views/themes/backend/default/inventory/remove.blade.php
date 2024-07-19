@extends('themes.backend.default.layouts.app')

@section('pre-content')
    @php
        $data = [
            [
                'title' => __('Inventory'),
                'url' => route('admin.inventory.index')
            ],
            [
                'title' => __('Index'),
                'url' => ''
            ]
        ];
    @endphp
    <x-backend.breadcrumbs title="{{ __('Inventory') }}" :links="$data" />
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.inventory.search') }}" method="GET">
                        <div class="row">
                            <div class="col-lg-12 mb-3" style="display: none">
                                <div id="reader" style="width: 300px; height: auto"></div>
                                <div id="control" style="width: 300px; height: 25px" class="mt-3">
                                    <button id="start" type="button" class="btn btn-primary">Start</button>
                                    <button id="stop" type="button" class="btn btn-danger">Stop</button>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="query">{{ __('Product Search') }}</label>
                                    <div class="input-group">
                                        <input type="text" name="query" id="query" class="form-control" value="" placeholder="{{ __("Please Enter Product Name Or Product Barcode") }}">
                                        <button class="btn input-group-btn btn-dark waves-effect waves-light open_camera" type="button">{{ __('Open Camera') }}</button>
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

    <form action="{{ route('admin.inventory.remove-store') }}" method="post" id="inventoryForm">
        @csrf
        @method('POST')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mt-2">
                            <h3>{{ __('Products') }}</h3>

                            <div class="responsive-table-plugin">
                                <table class="table table-bordered" id="productList">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Product') }}</th>
                                            <th>{{ __('Quantity') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mt-2">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="repo_id">{{ __('Target Repo') }}</label>
                                    <select name="repo_id" id="repo_id" class="form-control" data-placeholder="{{ __('Select Repo') }}">
                                        @foreach($repos as $repo)
                                            <option value="{{ $repo->id }}">{{ $repo->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="person">{{ __('Person') }}</label>
                                    <select name="user_id" id="user" class="form-control">
                                        <option value="">{{ __('Select Person') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12">
                                <label for="description" class="form-label">{{ __('Description') }} <span class="required">*</span> </label>
                                <textarea name="description" id="description" cols="30" rows="7" class="form-control" required></textarea>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>

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
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="{{ asset('themes/backend/default/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $(document).ready(function(){

            $(document).on('change', 'select#user', function(){
                var value = $(this).val();
                if(value === 'other'){
                    $('input#person').prop('disabled', false).removeClass('bg-soft-dark');
                }else{
                    $('input#person').val('').prop('disabled', true).addClass('bg-soft-dark');
                }
            });

            const html5QrCode = new Html5Qrcode("reader", {
                fps: 10,
                qrbox: { width: 250, height: 250 },
                focusMode: "continuous",
                experimentalFeatures: { useBarCodeDetectorIfSupported: true },
                rememberLastUsedCamera: true,
                verbose: true }
            );
            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                html5QrCode.stop();
                document.getElementById('query').value = decodedText;

                //buraya bir ajax ya da form submit işlemi yapılacak
            };
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };

            $(document).on('click', 'button#start', function(){
                html5QrCode.start({ facingMode: "environment"}, config, qrCodeSuccessCallback);
            });

            $(document).on('click', 'button#stop', function(){
                html5QrCode.stop();
            });

            $(document).on('click', 'button.open_camera', function(){
                $('#reader').parent().toggle();
                html5QrCode.start({ facingMode: "environment"}, config, qrCodeSuccessCallback);
            });

            // Search product
            $(document).on('keyup', 'input#query', function(){
                var query = $(this).val();
                if(query.length > 2){
                    $.ajax({
                        url: "{{ route('admin.inventory.search') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            query: query
                        },
                        success: function(response){
                            if (response.status === 'success') {
                                let html = '<div class="list-group">';
                                $(response.data).each(function(index, product){
                                    html += '<a href="javascript:void(0)" class="list-group-item list-group-item-action add-to-cart" data-name="'+product.name+'" data-id="'+product.id+'">'+product.name+'</a>';
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
                var name = $(this).data('name');

                var html = '<tr>';
                html += '<td>'+name+'</td>';
                html += '<td><input type="number" name="products['+id+']" data-productId="'+id+'" class="form-control product_'+ id +'" value="1"></td>';
                html += '<td><a href="#" class="btn btn-danger remove-from-cart" data-id="'+id+'"><i class="fas fa-trash-alt"></i></a></td>';
                html += '</tr>';

                $('#productList tbody').append(html);
                $('.search-result-box').find('div').empty().fadeOut();
            });

            // Remove from cart
            $(document).on('click', 'a.remove-from-cart', function(){
                var id = $(this).data('id');
                $(this).closest('tr').remove();
            });

            //source repo product control
            $(document).on('change', 'select#source_id', function(){
                let source_id = $(this).val();

                if ( source_id !== 0 ) {
                    let products = [];
                    $('input[name^="products"]').each(function(){
                        let product_id = $(this).data('productid');
                        let quantity = $(this).val();
                        products.push({product_id: product_id, quantity: quantity});
                    });

                    $.ajax({
                        url: "{{ route('admin.inventory.source-repo-check') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            source_id: source_id,
                            products : products
                        },
                        before: function(){
                            $('input').removeClass('parsley-error');
                            $('ul.parsley-errors-list').remove();
                        },
                        success: function(response){
                            if ( response.data.length ) {

                                for(let i = 0; i <= response.data.length - 1 ; i++) {
                                    let product_id = response.data[i].product_id;
                                    let status = response.data[i].status;

                                    if ( status === 'error') {
                                        $('input.product_'+product_id).addClass('parsley-error');
                                    }
                                }
                            }
                        }
                    });
                }
            });

            //submit form
            {{--$(document).on('submit', 'form#inventoryForm', function(e){--}}
            {{--    e.preventDefault();--}}

            {{--    let source_id = $('select#source_id').val();--}}
            {{--    let target_id = $('select#target_id').val();--}}

            {{--    if ( source_id === target_id ) {--}}
            {{--        Swal.fire({--}}
            {{--            icon: 'error',--}}
            {{--            title: 'Oops...',--}}
            {{--            text: '{{ __('Source and Target Repo can not be same!') }}',--}}
            {{--        });--}}

            {{--        return false;--}}
            {{--    }--}}

            {{--    $(this).trigger('submit');--}}
            {{--})--}}
        });
    </script>
@endpush
