<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Repo;
use App\Models\Product;
use App\Models\RepoHasProducts;
use App\Models\RepoRequest;
use Illuminate\Http\Request;

class MyReposController extends Controller
{

    public function index()
    {
        $repo = Repo::where('user_id', auth()->id())->first();
        $products = RepoHasProducts::where(['repo_id' => $repo->id])->get();

        return view('my-repos.index', compact('repo', 'products'));
    }

    public function myRequests()
    {
        $userId = auth()->id();
        $requests = RepoRequest::where(['user_id' => $userId])->get();
        return view('my-repos.my-requests', compact('requests'));
    }

    public function newRequest()
    {
        $repo = Repo::where('user_id', auth()->id())->first();
        $products = Product::all();

        return view('my-repos.new-request', compact('repo', 'products'));
    }

    public function storeRequest(Request $request)
    {
        $validated = \Validator::make($request->all(), [
            'product' => 'required|array',
            'product.*' => 'required|exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
        ]);

        $repo = Repo::where('user_id', auth()->id())->first();

        $validated = $validated->validated();

        $data = collect($validated['product'])->map(function ($product, $key) use ($validated) {
            return [
                'product_id' => $product,
                'quantity' => $validated['quantity'][$key],
            ];
        });

        $save = $repo->requests()->create([
            'repo_id' => $repo->id,
            'user_id' => auth()->id(),
            'data' => $data,
            'status' => 'pending',
        ]);

        return $save ? response()->json(['status' => 'success', 'message' => 'Request submitted successfully']) : response()->json(['status' => 'error', 'message' => 'Failed to submit request']);
    }

    public function showRequest($id)
    {
        $request = RepoRequest::where(['id' => $id, 'user_id' => auth()->id()])->first();
        return view('my-repos.show-request', compact('request'));
    }
}
