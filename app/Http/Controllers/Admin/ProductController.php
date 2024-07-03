<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\ProductCategory;
use App\Models\Product;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-product|edit-product|delete-product')->only(['index', 'show']);
        $this->middleware('permission:create-product')->only(['create', 'store']);
        $this->middleware('permission:edit-product')->only(['edit', 'update']);
        $this->middleware('permission:delete-product')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ProductCategory::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();
        $save = Product::create($validated);

        if ($save) {

            //image save
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->extension();
                $image->move(public_path('images'), $imageName);
                $save->image = $imageName;
                $save->save();
            }

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
        } else {
            return redirect()->route('admin.products.index')->with('error', 'Product creation failed');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        if ($product) {
            return view('products.show', compact('product'));
        } else {
            return redirect()->route('admin.products.index')->with('error', 'Product not found');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::find($id);
        if ($product) {
            $categories = ProductCategory::all();
            return view('products.edit', compact('product', 'categories'));
        } else {
            return redirect()->route('admin.products.index')->with('error', 'Product not found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $validated = $request->validated();
        $product = Product::find($id);
        if ($product) {
            $product->update($validated);

            //image save
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->extension();
                $image->move(public_path('images'), $imageName);
                $product->image = $imageName;
                $product->save();
            }

            return redirect()->route('admin.products.edit', $id)->with('success', 'Product updated successfully');
        } else {
            return redirect()->route('admin.products.index')->with('error', 'Product not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
