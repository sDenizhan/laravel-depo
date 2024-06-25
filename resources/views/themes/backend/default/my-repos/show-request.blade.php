@extends('themes.backend.default.layouts.app')

@section('pre-content')
    @php
        $data = [
            [
                'title' => __('My Inventory'),
                'url' => route('admin.my-repo.index')
            ],
            [
                'title' => __('Request Details'),
                'url' => ''
            ]
        ];
    @endphp
    <x-backend.breadcrumbs title="{{ __('My Requests') }}" :links="$data" />
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    {{ __('Request Information') }}
                </div>
                <div class="float-end">
                    <a href="{{ route('admin.my-repo.my-requests') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">

                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Status :') }}</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            @if ($request->status == 'pending')
                                <span class="badge bg-warning text-dark">{{ ucfirst($request->status) }}</span>
                            @elseif ($request->status == 'approved')
                                <span class="badge bg-success">{{ ucfirst($request->status) }}</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst($request->status) }}</span>
                            @endif
                        </div>
                    </div>

                    @if($request->status == 'rejected')
                        <div class="mb-3 row">
                            <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Rejection Reason :') }}</strong></label>
                            <div class="col-md-6" style="line-height: 35px;">
                                {{ $request->rejected_reason }}
                            </div>
                        </div>
                    @endif

                    <div class="mb-3 row">
                        <label for="roles" class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Products :') }}</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            @php
                                // Tüm product_id değerlerini toplama
                                $productIds = collect($request->data)->pluck('product_id')->all();
                                // Ürünleri eager loading ile getirme
                                $products = App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');
                            @endphp

                            @if (!is_null($request->data))
                                <ul>
                                    @foreach ($request->data as $productData)
                                        @php
                                            $product = $products->get($productData['product_id']);
                                        @endphp

                                        @if($product)
                                            <li>{{ $product->name }} - {{ $productData['quantity'] }}</li>
                                        @else
                                            <li>{{ $productData['product_id'] }} - {{ $productData['quantity'] }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                {{ __('No products found') }}
                            @endif
                        </div>
                    </div>

            </div>
        </div>
    </div>
</div>
@endsection
