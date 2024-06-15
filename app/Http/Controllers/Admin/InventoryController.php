<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInventoryRequest;
use App\Models\Repo;
use App\Models\Product;
use App\Models\RepoHasProducts;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $repos = Repo::all();
        return view('inventory.index', compact('repos'));
    }

    public function search(Request $request)
    {
        $query = $request->post('query') ?? '';
        $product = Product::where('name', 'like', "%$query%")->orWhere('barcode', 'like', "%$query%")->first();
        if ( $product ) {
            $repos = Repo::all();
            $html = view('components.backend.inventory.product-form', compact('product', 'repos'))->render();
            return response()->json(['status' => 'success', 'html' => $html]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Product not found.']);
        }
    }

    public function store(StoreInventoryRequest $request)
    {
        $validated = $request->validated();
        $save = RepoHasProducts::create($validated);

        if ( $save ) {
            return redirect()->route('admin.inventory.index')->with('success', 'Product added to inventory successfully.');
        } else {
            return redirect()->route('admin.inventory.index')->with('error', 'Failed to add product to inventory.');
        }
    }
}
