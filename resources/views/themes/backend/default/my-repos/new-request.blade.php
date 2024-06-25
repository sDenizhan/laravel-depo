@extends('themes.backend.default.layouts.app')

@section('pre-content')
    @php
        $data = [
            [
                'title' => __('My Inventory'),
                'url' => route('admin.my-repo.index')
            ],
            [
                'title' => __('New Request'),
                'url' => ''
            ]
        ];
    @endphp
    <x-backend.breadcrumbs title="{{ __('New Request') }}" :links="$data" />
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="mb-3">
                                <label for="product" class="form-label">{{ __('Product') }}</label>
                                <select class="form-select select2" id="product" name="product">
                                    <option value="#" selected>{{ __('Select Product') }}</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">{{ __('Quantity') }}</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="{{ __('Enter Quantity') }}">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="mt-3">
                                <button type="button" class="btn btn-primary add">{{ __('Add') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('admin.my-repo.store-request') }}" id="RequestForm">
                        @csrf
                        @method('POST')

                        <div class="row submit mt-3">
                            <div class="col-lg-2">
                                <button type="submit" class="btn btn-secondary">{{ __('Submit') }}</button>
                            </div>
                        </div>
                    </form>

                    <div class="alert mt-3" style="display: none"></div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <!-- third party css -->
    <link href="{{ asset('themes/backend/default/assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('scripts')
    <script src="{{ asset('themes/backend/default/assets/libs/select2/js/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();


            $(document).on('click', 'button.add', function(e){
                e.preventDefault();

                var form = $('#RequestForm');
                var product = $('#product').val();
                var quantity = $('#quantity').val();
                var product_name = $('#product option:selected').text();

                if(product == '#') {
                    alert('Please select a product');
                    return false;
                }

                if(quantity == '') {
                    alert('Please enter quantity');
                    return false;
                }

                var html = '<div class="row mb-3 products" id="product-'+product+'">';
                html += '<div class="col-lg-5">';
                html += '<input type="hidden" name="product[]" value="'+product+'">';
                html += '<input type="text" class="form-control" value="'+product_name+'" readonly>';
                html += '</div>';
                html += '<div class="col-lg-5">';
                html += '<input type="text" class="form-control" name="quantity[]" value="'+quantity+'">';
                html += '</div>';
                html += '<div class="col-lg-2">';
                html += '<button type="button" class="btn btn-danger remove" data-id="'+product+'">{{ __('Remove') }}</button>';
                html += '</div>';
                html += '</div>';
                form.find('div.submit').prepend(html);
            });

            $(document).on('submit', 'form#RequestForm', function(e){
                e.preventDefault();

                var form = $(this);
                var url = form.attr('action');
                var data = form.serialize();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        if ( response.status === 'success') {

                            $('div.alert').removeClass('alert-danger').addClass('alert-success').text(response.message).show();

                            setTimeout(function(){
                                form.find('div.alert').removeClass('alert-success').text('').hide();
                            }, 3000);

                            form.trigger('reset');
                            form.find('div.products').remove();
                        } else {
                            $('div.alert').removeClass('alert-success').addClass('alert-danger').text(response.message).show();

                            setTimeout(function(){
                                form.find('div.alert').removeClass('alert-danger').text('').hide();
                            }, 3000);
                        }
                    }
                });
            });
        });
    </script>
@endpush
