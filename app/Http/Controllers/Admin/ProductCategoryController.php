<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-product-category|edit-product-category|delete-product-category')->only('index', 'show');
        $this->middleware('permission:create-product-category')->only('create', 'store');
        $this->middleware('permission:edit-product-category')->only('edit', 'update');
        $this->middleware('permission:delete-product-category')->only('destroy');
    }


    public function index()
    {
        $categories = ProductCategory::all();
        return view('product-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('product-categories.create');
    }

    public function store(StoreProductCategoryRequest $request)
    {
        $valdated = $request->validated();
        ProductCategory::create($valdated);

        return redirect()->route('admin.product-categories.create')->withSuccess('Product category created successfully');
    }

    public function edit(?int $id)
    {
        $category = ProductCategory::find($id);
        return view('product-categories.edit', compact('category'));
    }

    public function update(UpdateProductCategoryRequest $request, ProductCategory $productCategory)
    {
        $valdated = $request->validated();
        $productCategory->update($valdated);

        return redirect()->route('admin.product-categories.edit', $productCategory->id)->withSuccess('Product category updated successfully');
    }

    public function destroy(ProductCategory $productCategory)
    {
        $isDeleted = $productCategory->delete();
        return response()->json(['success' => $isDeleted], $isDeleted ? 200 : 400);
    }

    public function show(ProductCategory $productCategory)
    {
        redirect()->route('admin.product-categories.index');
    }
}
