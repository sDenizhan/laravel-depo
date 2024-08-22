<?php

namespace App\Http\Controllers\Admin;

use App\Events\PrescriptionCreated;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Prescription;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Repo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PrescriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-prescription|edit-prescription|delete-prescription')->only(['index', 'show']);
        $this->middleware('permission:create-prescription')->only(['create', 'store']);
        $this->middleware('permission:edit-prescription')->only(['edit', 'update']);
        $this->middleware('permission:delete-prescription')->only(['destroy']);
    }

    public function index()
    {

        $prescriptions = Prescription::query()
                        ->when(!auth()->user()->hasRole('Super Admin'), function ($query) {
                            return $query->where(['user_id' => auth()->id()]);
                        })
                        ->when(auth()->user()->hasRole('Super Admin'), function ($query){
                            return $query;
                        })->get();

        return view('prescriptions.index', compact('prescriptions'));
    }

    public function create()
    {
        $medicineCategory = ProductCategory::where(['use_for_prescription' => 1])->first();
        if ( $medicineCategory ) {

            $doctors = Doctor::all();
            $hospitals = Repo::where(['is_hospital' => 1])->get();
            $medicines = Product::where(['category_id' => $medicineCategory->id])->get();

            return view('prescriptions.create', compact('medicines', 'doctors', 'hospitals'));
        } else {
            return redirect()->route('admin.prescriptions.index')->with('error', 'No medicine category found for prescription');
        }
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'patient_name' => 'required|string',
            'patient_birthday' => 'nullable|string',
            'box' => 'required|array',
            'box.*' => 'required|integer',
            'dosage' => 'required|array',
            'dosage.*' => 'required|string',
            'note' => 'nullable|array',
            'note.*' => 'nullable|string',
            'doctor_id' => 'required|integer',
            'hospital_id' => 'required|integer',
        ]);

        if ( $validate->fails() ) {
            return response()->json(['status' => 'error', 'message' => $validate->errors()->first()], 400);
        }

        $input = $validate->validated();
        $prescription = Prescription::create([
            'user_id' => auth()->id(),
            'patient_name' => $input['patient_name'],
            'patient_birthday' => $input['patient_birthday'],
            'doctor_id' => $input['doctor_id'],
            'hospital_id' => $input['hospital_id']
        ]);

        foreach ($input['box'] as $productId => $box) {
            $prescription->medicines()->create([
                'prescription_id' => $prescription->id,
                'product_id' => $productId,
                'box' => $input['box'][$productId],
                'dosage' => $input['dosage'][$productId],
                'note' => $input['note'][$productId],
            ]);
        }

        event(new PrescriptionCreated($prescription));

        return response()->json(['status' => 'success', 'message' => 'Prescription created successfully'], 201);
    }

    public function show($id)
    {
        $prescription = Prescription::query();
        $prescription->when(!auth()->user()->hasRole('Super Admin'), function ($query, $id) {
            return $query->where(['user_id' => auth()->id()]);
        });
        $prescription->when(auth()->user()->hasRole('Super Admin'), function ($query, $id) {
            return $query;
        });

        $prescription = $prescription->find($id);

        return view('prescriptions.show', compact('prescription'));
    }

    public function print($id)
    {
        $prescription = Prescription::query();
        $prescription->when(!auth()->user()->hasRole('Super Admin'), function ($query, $id) {
            return $query->where(['user_id' => auth()->id()]);
        });
        $prescription->when(auth()->user()->hasRole('Super Admin'), function ($query, $id) {
            return $query;
        });

        $prescription = $prescription->find($id);

        return view('prescriptions.print', compact('prescription'));
    }

    public function edit($id)
    {
        $prescription = Prescription::query();
        $prescription->when(!auth()->user()->hasRole('Super Admin'), function ($query, $id) {
            return $query->where(['user_id' => auth()->id()]);
        });
        $prescription->when(auth()->user()->hasRole('Super Admin'), function ($query, $id) {
            return $query;
        });

        $prescription = $prescription->find($id);


        return view('prescriptions.edit', compact('prescription'));
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        if ( ! auth()->user()->can('delete-prescriptions') ) {
            return redirect()->route('admin.prescriptions.index')->with('error', 'You do not have permission to delete prescription');
        }

        $prescription = Prescription::find($id);
        $prescription->delete();
        return redirect()->route('admin.prescriptions.index')->with('success', 'Prescription deleted successfully');
    }

    public function search(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'query' => 'required|string'
        ]);

        if ( $validate->fails() ) {
            return response()->json(['status' => 'error', 'message' => $validate->errors()->first()], 400);
        }

        $input = $validate->validated();
        $query = $input['query'];

        $medicineCategory = ProductCategory::where(['use_for_prescription' => 1])->first();
        $product = Product::where(['category_id' => $medicineCategory->id])
                            ->where('name', 'like', '%'. $query .'%')
                            ->orWhere('barcode', 'like', '%'. $query .'%')
                            ->get();

        if ( $product ) {
            return response()->json(['status' => 'success', 'message' => '', 'products' => $product], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'No product found'], 404);
        }
    }

    public function select(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validated->fails()) {
            return response()->json(['status' => 'error', 'message' => $validated->errors()->first()], 400);
        }

        $product = Product::find($request->id);
        if ($product) {
            return response()->json(['status' => 'success', 'message' => '', 'product' => $product], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }
    }
}
