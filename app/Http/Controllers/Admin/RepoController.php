<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRepoRequest;
use App\Http\Requests\UpdateRepoRequest;
use App\Models\Logs;
use App\Models\Repo;
use App\Models\RepoLog;
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
        if ( $request->is_main ) {
            Repo::where('is_main', true)->update(['is_main' => false]);
        }

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

        $logs = RepoLog::with('product')->where('repo_id', $id)->orderBy('created_at', 'desc')->limit(10)->get();
        return view('repos.show', compact('repo', 'logs', 'products'));
    }

    public function edit($id)
    {
        $repo = Repo::find($id);
        $users = User::all();
        return view('repos.edit', compact('repo', 'users'));
    }

    public function update(UpdateRepoRequest $request, $id)
    {
        $repo = Repo::find($id);

        if ( $request->is_main ) {
            Repo::where('is_main', true)->update(['is_main' => false]);
        }

        $repo->update($request->validated());
        return redirect()->route('admin.repos.edit', ['repo' => $repo->id])->withSuccess('Repo updated.');
    }

    public function destroy($id)
    {
        $repo = Repo::find($id);
        $repo->delete();
        return response()->json(['status' => 'success', 'message' => 'Repo deleted.']);
    }
}
