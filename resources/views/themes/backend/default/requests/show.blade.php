@extends('themes.backend.default.layouts.app')

@section('pre-content')
    @php
        $data = [
            [
                'title' => __('Requests'),
                'url' => route('admin.requests.index')
            ],
            [
                'title' => __('Index'),
                'url' => ''
            ]
        ];
    @endphp
    <x-backend.breadcrumbs title="{{ __('Request Details') }}" :links="$data" />
@endsection

@section('content')

    @if($request->status == 'rejected')
        <div class="mb-2 row">
            <div class="col-lg-12">
                <div class="alert alert-danger">
                    <label for="name" class="col-md-4 col-form-label"><strong>{{ __('Rejection Reason :') }}</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $request->rejected_reason }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <form action="{{ route('admin.requests.update', ['request' => $request->id]) }}" method="post" id="updateForm">
            @method('PUT')
            @csrf
            <input type="hidden" name="id" value="{{ $request->id }}">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Request Actions') }}
                        @if ($request->status == 'pending')
                            <span class="badge bg-warning text-dark">{{ ucfirst($request->status) }}</span>
                        @elseif ($request->status == 'approved')
                            <span class="badge bg-success">{{ ucfirst($request->status) }}</span>
                        @else
                            <span class="badge bg-danger">{{ ucfirst($request->status) }}</span>
                        @endif
                    </div>
                    <div class="card-body">
                        @php
                            $productIds = collect($request->data)->pluck('product_id')->all();
                            $products = App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');
                        @endphp

                        @if (!empty($request->data) )
                            @foreach($request->data as $requestData)
                                @php($product = $products->get($requestData['product_id']))
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        {{ $product->name }}
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="quantity[{{$product->id}}]" id="quantity_{{ $product->id }}" value="{{ $requestData['quantity'] }}">
                                            </div>
                                            <div class="col-lg-6">
                                                <select name="repo[{{ $product->id }}]" id="repo_id" class="form-control">
                                                    <option value="0">{{ __('Select Repository') }}</option>
                                                    @forelse($repos as $repo)
                                                        <option value="{{ $repo->id }}">{{ $repo->name }}</option>
                                                    @empty
                                                        <option value="">{{ __('No repository found') }}</option>
                                                    @endforelse
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <div class="row">
                            <div id="result" class="alert"></div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">{{ __('Update Request') }}</button>
                                <button type="button" class="btn btn-danger cancel-request" data-requestid="{{ $request->id }}">{{ __('Reject Request') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('Edit Request Status') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                            <label for="reason" class="form-label">{{ __('Reason') }}</label>
                            <textarea name="rejected_reason" id="reason" class="form-control" rows="3"></textarea>
                        </div>
                        <input type="hidden" name="status" id="status" value="rejected" />
                        <input type="hidden" name="id" id="requestId" value="" />
                    </form>
                    <div id="result" class="alert" style="display: none">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary save">{{ __('Save') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            //check errors
            function checkErrors(){
                var errors = 0;
                $('.is-invalid').each(function() {
                    errors++;
                });

                return errors;
            }

            //cancel request
            $('.cancel-request').on('click', function() {
                var id = $(this).attr('data-requestid');
                $('#staticBackdrop').modal('show');
                $('#requestId').val(id);
            });

            //check product quantity on repos
            $(document).on('change', '#repo_id', function() {
                var select = $(this);
                var element = $(this).closest('.row').find('.invalid-feedback');
                var repoId = $(this).val();
                var productId = $(this).closest('.row').find('input[type="text"]').attr('id').split('_')[1];
                var quantity = $(this).closest('.row').find('input[type="text"]').val();
                var url = '{{ route('admin.requests.check-product-quantity') }}';
                var data = {
                    repo_id: repoId,
                    product_id: productId,
                    quantity: quantity,
                    _token : '{{ csrf_token() }}'
                };

                $.post(url, data, function(response) {
                    if ( response.status === 'error' ) {
                        select.addClass('is-invalid').removeClass('is-valid');
                        element.addClass('is-invalid').html(response.message).show();
                    } else {
                        select.addClass('is-valid').removeClass('is-invalid');
                        element.removeClass('is-invalid').removeClass('is-valid').html('').show();
                    }
                });
            });

            //send form
            $('form#updateForm').on('submit', function (e){
                e.preventDefault();

                if ( checkErrors() > 0 ) {
                    $('#result').removeClass('alert-success').addClass('alert-danger').html('{{ __('Please check the form errors') }}').show();
                    return false;
                }

                var form = $(this);
                var url = form.attr('action');

                $.post(url, form.serialize(), function(response) {
                    if ( response.status === 'success' ) {
                        $('#result').removeClass('alert-danger').addClass('alert-success').html(response.message).show();
                    } else {
                        $('#result').removeClass('alert-success').addClass('alert-danger').html(response.message).show();
                    }

                    setTimeout(() => {
                        $('#result').hide();
                        window.location.reload();
                    }, 3000);
                });
            });

            //update request
            $('.save').on('click', function(e) {
                e.preventDefault();

                var form = $('#staticBackdrop form');

                $.post('{{ route('admin.requests.status-update') }}', form.serialize(), function(response) {
                    if ( response.status === 'success' ) {
                        $('#result').removeClass('alert-danger').addClass('alert-success').html(response.message).show();
                    } else {
                        $('#result').removeClass('alert-success').addClass('alert-danger').html(response.message).show();
                    }

                    setTimeout(() => {
                        $('#result').hide();
                        $('#staticBackdrop').modal('hide');
                        window.location.reload();
                    }, 3000);
                });
            });

        });
    </script>
@endpush
