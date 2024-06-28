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
    <div class="row searchForm">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('admin.inventory.search') }}" method="GET">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-lg-12 mb-3" style="display: none">
                                <div id="reader" style="width: 300px; height: auto"></div>
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
            //html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);

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
                                $('.result').html(response.html);
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
