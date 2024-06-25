<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RepoRequest;
use Illuminate\Http\Request;

class RequestsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Super Admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = RepoRequest::all();
        return view('requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $request = RepoRequest::find($id);
        return view('requests.show', compact('request'));
    }

    public function statusUpdate(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'status' => 'required|in:pending,approved,rejected',
            'rejected_reason' => 'required_if:status,rejected',
            'id' => 'required|exists:repo_requests,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);
        }

        $repoRequest = RepoRequest::find($request->id);
        $update = $repoRequest->update([
            'status' => $request->status,
            'rejected_reason' => $request->rejected_reason
        ]);

        return $update ?
            response()->json(['status' => 'success', 'message' => 'Request status updated successfully']) :
            response()->json(['status' => 'error', 'message' => 'Failed to update request status']);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
