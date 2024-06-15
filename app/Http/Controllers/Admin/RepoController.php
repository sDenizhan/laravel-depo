<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRepoRequest;
use App\Models\Repo;
use App\Models\User;
use Illuminate\Http\Request;

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
        return view('repos.show', compact('repo'));
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
