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
                                        <button class="btn input-group-text btn-dark waves-effect waves-light search" type="button">{{ __('Search') }}</button>
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
                <form action="{{ route('admin.inventory.search') }}" method="GET">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="source_id">{{ __('Source Repo') }}</label>
                                    <select name="source_id" id="source_id" class="form-control">
                                        <option value="">{{ __('Select Repo') }}</option>
                                        <option value="0">{{ __('Supplier') }}</option>
                                        @foreach($repos as $repo)
                                            <option value="{{ $repo->id }}">{{ $repo->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="target_id">{{ __('Target Repo') }}</label>
                                    <select name="target_id" id="target_id" class="form-control">
                                        <option value="">{{ __('Select Repo') }}</option>
                                        <option value="0">{{ __('Somebody') }}</option>
                                        @foreach($repos as $repo)
                                            <option value="{{ $repo->id }}">{{ $repo->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="person">{{ __('Person') }}</label>
                                    <input type="text" name="person" id="person" class="form-control" value="" placeholder="{{ __('Person') }}">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="patient">{{ __('Patient') }}</label>
                                    <input type="text" name="patient" id="patient" class="form-control" value="" placeholder="{{ __('Patient') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12">
                                <label for="description" class="form-label">{{ __('Description') }}</label>
                                <textarea name="description" id="description" cols="30" rows="7" class="form-control"></textarea>
                            </div>
                        </div>

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

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        $(document).ready(function(){

            const html5QrCode = new Html5Qrcode("reader", {
                fps: 10,
                qrbox: { width: 250, height: 250 },
                focusMode: "continuous",
                experimentalFeatures: { useBarCodeDetectorIfSupported: true },
                rememberLastUsedCamera: true,
                verbose: true }
            );
            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                console.log(decodedResult);
                html5QrCode.stop();
                document.getElementById('query').value = decodedText;
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

            $(document).on('click', 'button.search', function(){
                var query = $('#query').val();
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
                                console.log(response.data);
                                var item = response.data;
                                var html = '';
                                    html += '<tr>';
                                    html += '<td style="max-width: 50%" ">'+item['name']+'</td>';
                                    html += '<td style="max-width: 20%"><input type="number" name="products['+item['id']+'][quantity]" class="form-control" value="1"></td>';
                                    html += '<td style="max-width: 30%"><button class="btn btn-danger remove" type="button">Remove</button></td>';
                                    html += '</tr>';
                                $('table#productList').find('tbody').append(html);
                                console.log(html);
                            } else {

                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
