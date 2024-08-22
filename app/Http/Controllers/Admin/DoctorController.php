<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Models\Doctor;
use App\Models\Repo;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-doctor|edit-doctor|delete-doctor')->only(['index', 'show']);
        $this->middleware('permission:create-doctor')->only(['create', 'store']);
        $this->middleware('permission:edit-doctor')->only(['edit', 'update']);
        $this->middleware('permission:delete-doctor')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::all();
        return view('doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hospitals = Repo::where(['is_hospital' => '1'])->get();
        return view('doctors.create', compact('hospitals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDoctorRequest $request)
    {
        $validated = $request->validated();
        $created = Doctor::create($validated);

        if ( $created ) {
            return redirect()->route('admin.doctors.index')->with('success', 'Doctor created successfully');
        } else {
            return redirect()->route('admin.doctors.index')->with('error', 'Doctor creation failed');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.doctors.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $doctor = Doctor::find($id);
        $hospitals = Repo::where(['is_hospital' => '1'])->get();
        return view('doctors.edit', compact('doctor', 'hospitals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDoctorRequest $request, string $id)
    {
        $validated = $request->validated();
        $doctor = Doctor::find($id);
        $updated = $doctor->update($validated);

        if ( $updated ) {
            return redirect()->route('admin.doctors.index')->with('success', 'Doctor updated successfully');
        } else {
            return redirect()->route('admin.doctors.index')->with('error', 'Doctor update failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ( !auth()->user()->can('delete-doctor') ) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $doctor = Doctor::find($id);
        $deleted = $doctor->delete();

        if ( $deleted ) {
            return response()->json(['status' => 'success', 'message' => 'Doctor deleted successfully']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Doctor deletion failed']);
        }
    }
}
