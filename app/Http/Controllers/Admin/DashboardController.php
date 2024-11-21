<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\ProductCategory;
use App\Models\Repo;
use App\Models\RepoHasProducts;
use Illuminate\Http\Request;
use mysql_xdevapi\Collection;

class DashboardController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $prescriptions = Prescription::query()
                        ->when(!auth()->user()->hasRole('Super Admin'), function ($query) {
                            return $query->where(['user_id' => auth()->id()]);
                        })
                        ->when(auth()->user()->hasRole('Super Admin'), function ($query){
                            return $query;
                        })->limit(10)->get();

        //logs
        $logs = \App\Models\RepoLog::query()
                                    ->when(auth()->user()->hasRole('Super Admin'), function ($query){
                                        return $query;
                                    })
                                    ->when(!auth()->user()->hasRole('Super Admin'), function ($query){
                                        return $query->where('user_id', auth()->id());
                                    })
                                    ->orderBy('id', 'desc')->limit(10)->get();

        //hastaneler
        $hospitals = Repo::where('is_hospital', 1)->get();
        $products = RepoHasProducts::with('product')->whereIn('repo_id', $hospitals->pluck('id')->toArray())->get();
        $data = collect($products)->map(function ($item) use ($hospitals, $products) {
            $quantities = [];
            foreach ($hospitals as $hospital) {
                $quantities[$hospital->id] = $products->filter(function ($product) use ($hospital) {
                    return $product->repo_id == $hospital->id;
                })->where('product_id', $item->product_id)->sum('quantity');
            }

            return [
                'name' => $item->product->name,
                'data' => array_values($quantities)
            ];
        });

        //category product count in main repo
        $categories = ProductCategory::pluck('name')->toArray();
        $repo = Repo::where('is_main', 1)->first();
        $products = RepoHasProducts::with('product')->where('repo_id', $repo->id)->get();
        $categoryData = collect($products)->map(function ($item) use ($categories, $products) {
            $quantities = [];
            foreach ($categories as $category) {
                $quantities[$category] = $products->filter(function ($product) use ($category) {
                    return $product->product->category->name == $category;
                })->where('product_id', $item->product_id)->sum('quantity');
            }

            return [
                'name' => $item->product->category->name,
                'data' => collect($quantities)->values()->sum()
            ];
        });

        $mainRepoCategories = $categoryData->pluck('name')->toArray();
        $mainRepoData = $categoryData->pluck('data')->toArray();

        return view('themes.backend.default.home', compact('prescriptions', 'logs', 'hospitals', 'data', 'mainRepoData', 'mainRepoCategories'));
    }

    public function notifyReaded(Request $request)
    {
        if ( $request->notifyId ) {
            $user = auth()->user();
            $user->unreadNotifications->where('id', $request->notifyId)->markAsRead();
        }
    }

}
