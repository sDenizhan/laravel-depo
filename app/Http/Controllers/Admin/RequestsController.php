<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Repo;
use App\Models\RepoHasProducts;
use App\Models\RepoLog;
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $request = RepoRequest::find($id);
        $repos = Repo::all();
        return view('requests.show', compact('request', 'repos'));
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


    public function check(Request $request)
    {
        $repo = RepoHasProducts::where([
            'repo_id' => $request->repo_id,
            'product_id' => $request->product_id
        ])->first();

        if ( $repo ) {
            if ( $repo->quantity >= $request->quantity ) {
                return response()->json(['status' => 'success', 'message' => __('Enough quantity in the inventory.')]);
            } else {
                return response()->json(['status' => 'error', 'message' => __('Not enough quantity in the inventory.')]);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => __('Product not found in the inventory.')]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = \Validator::make($request->all(), [
            'quantity' => 'required|array',
            'id' => 'required|exists:repo_requests,id',
            'repo' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);
        }

        $repoRequest = RepoRequest::find($request->id);

        if ( $repoRequest->status !== 'pending' ) {
            return response()->json(['status' => 'error', 'message' => 'Request already processed.']);
        }

        $data = collect($request->quantity)->map(function ($quantity, $product_id) use ($request) {
            return [
                'repo_id' => $request->repo[$product_id],
                'product_id' => $product_id,
                'quantity' => $quantity
            ];
        });

        foreach ($data as $row) {

            //target repo id
            $targetRepoId = $repoRequest->repo_id;

            //source repo id
            $sourceRepo = RepoHasProducts::where([
                'repo_id' => $row['repo_id'],
                'product_id' => $row['product_id']
            ])->first();

            if (!$sourceRepo ) {
                return response()->json(['status' => 'error', 'message' => __('Product not found in the repo.')]);
            }

            // check if source repo has enough quantity
            if ( $sourceRepo->quantity < $row['quantity'] ) {
                return response()->json(['status' => 'error', 'message' => __('Not enough quantity in the :name repo.', ['name' => $sourceRepo->repo->name])]);
            }

            //transfer
            $sourceRepo->quantity -= $row['quantity'];
            $sourceRepo->save();

            $targetRepo = RepoHasProducts::where([
                'repo_id' => $targetRepoId,
                'product_id' => $row['product_id']
            ])->first();

            if ( $targetRepo ) {
                $targetRepo->quantity += $row['quantity'];
                $targetRepo->save();
            } else {
                RepoHasProducts::create([
                    'repo_id' => $targetRepoId,
                    'product_id' => $row['product_id'],
                    'quantity' => $row['quantity']
                ]);
            }

            //target log
            $targetLog = RepoLog::create([
                'repo_id' => $targetRepoId,
                'product_id' => $row['product_id'],
                'user_id' => auth()->id(),
                'count' => $row['quantity'],
                'action' => 'in',
                'data' => $row
            ]);

            //source log
            $sourceLog = RepoLog::create([
                'repo_id' => $sourceRepo->repo_id,
                'product_id' => $row['product_id'],
                'user_id' => auth()->id(),
                'count' => $row['quantity'],
                'action' => 'out',
                'data' => $row
            ]);
        }

        $repoRequest->status = 'approved';
        $repoRequest->save();

        return response()->json(['status' => 'success', 'message' => 'Request processed successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
