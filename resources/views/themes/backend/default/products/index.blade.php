@extends('themes.backend.default.layouts.app')

@section('pre-content')
    @php
        $data = [
            [
                'title' => __('Products'),
                'url' => route('admin.products.index')
            ],
            [
                'title' => __('Index'),
                'url' => ''
            ]
        ];
    @endphp
    <x-backend.breadcrumbs title="{{ __('Products') }}" :links="$data" />
@endsection


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills card-header-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('admin.products.create') }}">{{ __('Add New') }}</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Price</th>
                            <th scope="col">Barcode</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <th scope="row">{{ $product->id }}</th>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>{{ join(' ', [$product->price, \App\Enums\Currency::from($product->currency)->name]) }}</td>
                                    <td>{{ $product->barcode }}</td>
                                    <td>
                                        @can('edit-product')
                                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                                        @endcan

                                        @can('delete-product')
                                            @if ($product->name!=Auth::user()->hasRole($product->name))
                                                <a href="{{ route('admin.products.destroy', ['product' => $product->id ]) }}" type="submit" class="btn btn-danger btn-sm remove_product"><i class="bi bi-trash"></i> Delete</a>
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <td colspan="3">
                                <span class="text-danger">
                                    <strong>No Product Found!</strong>
                                </span>
                                </td>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
