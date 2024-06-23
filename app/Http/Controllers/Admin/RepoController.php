<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRepoRequest;
use App\Models\Logs;
use App\Models\Repo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepoController extends Controller
{
    public function index()
    {
        $repos = Repo::all();
        return view('repos.index', compact('repos'));
    }

    public function create()
    {
        $users = User::all();
        return view('repos.create', compact('users'));
    }

    public function store(StoreRepoRequest $request)
    {
        Repo::create($request->validated());
        return redirect()->route('admin.repos.index')->withSuccess('Repo created.');
    }

    public function show($id)
    {
        $repo = Repo::findOrFail($id);
        $products = DB::table('repo_has_products')
            ->join('products', 'repo_has_products.product_id', '=', 'products.id')
            ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
            ->select('products.id', 'products.name', 'product_categories.name AS category_name', DB::raw('SUM(repo_has_products.quantity) AS total_quantity'))
            ->where('repo_has_products.repo_id', $id)
            ->groupBy('products.id', 'products.name', 'category_name')
            ->orderBy('total_quantity', 'desc')
            ->get();

        $logs = Logs::with('product')->where('data->repo_id', $id)->get();
        return view('repos.show', compact('repo', 'logs', 'products'));
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
