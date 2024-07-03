@extends('themes.backend.default.layouts.app')

@section('pre-content')
    @php
        $data = [
            [
                'title' => __('Products'),
                'url' => route('admin.products.index')
            ],
            [
                'title' => __('Add New'),
                'url' => ''
            ]
        ];
    @endphp
    <x-backend.breadcrumbs title="{{ __('Products') }}" :links="$data" />
@endsection

@section('content')
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Product Name') }}<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                                @error('name')
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 barcode">
                                <label for="barcode" class="form-label">{{ __('Product Barcode') }}<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control @error('barcode') is-invalid @enderror" id="barcode" name="barcode" value="{{ old('barcode') }}">
                                @error('barcode')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="price mb-3">
                                <label for="price" class="form-label">{{ __('Product Price') }}<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}">
                                @error('price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">{{ __('Product Category') }}<span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 brand">
                                <label for="brand" class="form-label">{{ __('Product Brand') }}</label>
                                <input type="text" class="form-control @error('brand') is-invalid @enderror" id="brand" name="brand" value="{{ old('brand') }}">
                                @error('brand')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 currency">
                                <label for="currency" class="form-label">{{ __('Product Currency') }}<span class="text-danger">*</span></label>
                                @php
                                    $currencies = \App\Enums\Currency::toArray();
                                @endphp
                                <select name="currency" id="currency" class="form-control @error('currency') is-invalid @enderror">
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->value }}">{{ $currency->name }}</option>
                                    @endforeach
                                </select>
                                @error('currency')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">{{ __('Product Description') }}</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-sm btn-primary">{{ __('Save') }}</button>
                </div>
            </div>
        </div>
        <div class="col-4 img">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="image" class="form-label">{{ __('Product Image') }}</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="img_show" style="overflow-x: hidden">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $('input[type="file"]').change(function(e){
                var fileName = e.target.files[0].name;
                var reader = new FileReader();
                reader.onload = function(e){
                    $('.img_show').html('<img src="'+e.target.result+'" width="256px" class="img-circle" alt="'+fileName+'">');
                }
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
@endpush
