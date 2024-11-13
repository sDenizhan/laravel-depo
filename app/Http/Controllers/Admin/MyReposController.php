<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Repo;
use App\Models\Product;
use App\Models\RepoHasProducts;
use App\Models\RepoRequest;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyReposController extends Controller
{

    public function index()
    {
        $repo = Repo::where('user_id', auth()->id())->first();

        if ( ! $repo) {
            $products = [];
        } else {
            $products = RepoHasProducts::where(['repo_id' => $repo->id])->get();
        }

        return view('my-repos.index', compact('repo', 'products'));
    }

    public function transfer()
    {
        $repo = Repo::where('user_id', auth()->id())->first();
        if ( ! $repo ) {
            return redirect()->route('admin.my-repo.index')->withErrors('You need to create a repo first');
        }

        $hospitals = Repo::where('is_hospital', 1)->get();
        $doctors = Doctor::all();

        return view('my-repos.transfer', compact('repo', 'hospitals', 'doctors'));
    }

    public function transferStore(Request $request)
    {
        $products = $request->post('products');
        $hospitalId = $request->post('hospital_id');
        $doctorId = $request->post('doctor_id');
        $patientName = $request->post('patient_name');
        $description = $request->post('description');

        //dd($request->all());

        //user repo check
        $repo = Repo::where('user_id', auth()->id())->first();

        if ( ! $repo ) {
            return response()->json(['status' => 'error', 'message' => 'You need to create a repo first']);
        }

        //user repo products check
        $repoProducts = RepoHasProducts::where('repo_id', $repo->id)->get();

        if ( $repoProducts->count() == 0 ) {
            return response()->json(['status' => 'error', 'message' => 'You need to add products to your repo first']);
        }

        //products has depo
        $productsCheck = RepoHasProducts::where(['repo_id' => $repo->id])->whereIn('product_id', array_keys($products))->get();

        if ( $productsCheck->count() == 0 ) {
            return response()->json(['status' => 'error', 'message' => 'You need to select products to transfer' ]);
        }

        $products = collect($products)->mapWithKeys(function ($quantity, $productId) {
            return [$productId => ['quantity' => $quantity]];
        });

        dd($products);
    }

    public function search(Request $request)
    {
        $repo = Repo::where('user_id', auth()->id())->first();

        if ( !$repo ) {
            return response()->json(['status' => 'error', 'message' => 'You need to create a repo first']);
        }

        $repoProducts = RepoHasProducts::where('repo_id', $repo->id)->get();

        if ( $repoProducts->count() == 0 ) {
            return response()->json(['status' => 'error', 'message' => 'You need to add products to your repo first']);
        }

        $validated = $request->validate([
            'query' => 'required',
        ]);

        $query = $validated['query'];

        $product = DB::table('repo_has_products')->select('products.*', 'repo_has_products.quantity')
            ->join('products', 'repo_has_products.product_id', '=', 'products.id')
            ->where('repo_has_products.repo_id', $repo->id)
            ->where(function (Builder $q) use ($query) {
                $q->where('products.name', 'like', "%$query%")
                    ->orWhere('products.barcode', 'like', "%$query%");
            })
            ->get();

        if ( $product->count() > 0 ) {
            return response()->json([
                'status' => 'success',
                'data' => $product
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Product not found.']);
        }
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
