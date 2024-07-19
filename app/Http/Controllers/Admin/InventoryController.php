<?php

namespace App\Http\Controllers\Admin;

use App\Events\LogInventoryAdded;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInventoryRequest;
use App\Models\Repo;
use App\Models\Product;
use App\Models\RepoHasProducts;
use App\Models\RepoLog;
use App\Models\User;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }
    public function index()
    {
        $repos = Repo::all();
        $users = User::all();
        return view('inventory.index', compact('repos', 'users'));
    }

    public function remove()
    {
        $repos = Repo::all();
        $users = User::all();
        return view('inventory.remove', compact('repos', 'users'));
    }

    public function remove_store(Request $request)
    {
        $validated = $request->validate([
            'repo_id' => 'required',
            'products' => 'required',
            'user_id' => 'required',
        ]);

        $repoId = $validated['repo_id'];

        $error = false;
        foreach ($validated['products'] as $product_id => $quantity) {
            $check = RepoHasProducts::where('repo_id', $repoId)->where('product_id', $product_id)->first();
            if ( is_null($check) || $check->quantity < $quantity ) {
                $error = true;
            }
        }

        if ( $error ) {
            return redirect()->back()->with('error', 'Not enough quantity in the inventory.');
        } else {
            foreach ($validated['products'] as $product_id => $quantity) {
                $check = RepoHasProducts::where('repo_id', $repoId)->where('product_id', $product_id)->first();
                $check->quantity -= $quantity;
                $check->save();
            }

            foreach ($validated['products'] as $product_id => $quantity) {
                $repoLog = new RepoLog();
                $repoLog->repo_id = $repoId;
                $repoLog->product_id = $product_id;
                $repoLog->user_id = $validated['user_id'];
                $repoLog->count = $quantity;
                $repoLog->action = 'out';
                $repoLog->data = $validated;
                $repoLog->save();
            }

            return redirect()->back()->with('success', 'Inventory removed successfully.');
        }

    }

    public function search(Request $request)
    {
        $query = $request->post('query') ?? '';
        $product = Product::where('name', 'like', "%$query%")->orWhere('barcode', 'like', "%$query%")->get();
        if ( $product ) {
            return response()->json(['status' => 'success', 'data' => $product]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Product not found.']);
        }
    }

    public function store(StoreInventoryRequest $request)
    {
        $validated = $request->validated();

        $repoId = $validated['repo_id'];

        foreach ($validated['products'] as $product_id => $quantity) {
            $check = RepoHasProducts::where('repo_id', $repoId)->where('product_id', $product_id)->first();
            if (!is_null($check)) {
                $check->quantity += $quantity;
                $check->save();
            } else {
                $repoHasProduct = new RepoHasProducts();
                $repoHasProduct->repo_id = $repoId;
                $repoHasProduct->product_id = $product_id;
                $repoHasProduct->quantity = $quantity;
                $repoHasProduct->save();
            }
        }

        foreach ($validated['products'] as $product_id => $quantity)
        {
            $repoLog = new RepoLog();
            $repoLog->repo_id = $repoId;
            $repoLog->product_id = $product_id;
            $repoLog->user_id = $validated['user_id'];
            $repoLog->count = $quantity;
            $repoLog->action = 'in';
            $repoLog->data = $validated;
            $repoLog->save();
        }
        return redirect()->back()->with('success', 'Inventory added successfully.');
    }

    public function check(Request $request)
    {
        $repoId = $request->source_id;
        $results = [];

        if ( $repoId != 0 ){
            foreach ($request->products as $product) {
                $check = RepoHasProducts::where('repo_id', $repoId)->where('product_id', $product['product_id'])->first();

                if (!is_null($check) && $check->quantity > $product['quantity'] ) {
                    $results[] = ['product_id' => $product['product_id'], 'status' => 'success', 'message' => ''];
                } else {
                    $results[] = ['product_id' => $product['product_id'], 'status' => 'error', 'message' => ''];
                }
            }

            return response()->json(['status' => 'success', 'data' => $results]);
        }
    }

    public function destore(Request $request)
    {

    }
}
