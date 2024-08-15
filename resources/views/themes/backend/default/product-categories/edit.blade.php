@extends('themes.backend.default.layouts.app')
@section('pre-content')
    @php
        $data = [
            [
                'title' => __('Product Categories'),
                'url' => route('admin.product-categories.index')
            ],
            [
                'title' => __('Index'),
                'url' => ''
            ]
        ];
    @endphp
    <x-backend.breadcrumbs title="{{ __('Product Categories') }}" :links="$data" />
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <form action="{{ route('admin.product-categories.update', ['product_category' => $category->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="{{ __('Name') }}" value="{{ old('name') ?? $category->name }}">
                                    @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="parent_id" class="form-label">{{ __('Parent Category') }}</label>
                                    <select name="parent_id" id="parent_id" class="form-control">
                                        <option value="0">{{ __('No Parent') }}</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ $category->parent_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="use_for_prescription" class="form-label">{{ __('Use For Prescription') }}</label>
                                    <select name="use_for_prescription" id="use_for_prescription" class="form-control">
                                        <option value="0" {{ $category->use_for_prescription == 0 ? 'selected' : '' }}>{{ __('No') }}</option>
                                        <option value="1" {{ $category->use_for_prescription == 1 ? 'selected' : '' }}>{{ __('Yes') }}</option>
                                    </select>
                                    @error('use_for_prescription')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-sm btn-primary">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
