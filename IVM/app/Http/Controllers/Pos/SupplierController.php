<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Auth;

class SupplierController extends Controller
{
    public function SupplierAll() {
        $suppliers = Supplier::latest()->get();
        return view('backend.supplier.supplier_all', compact('suppliers'));
    }

    public function SupplierAdd() {
        return view('backend.supplier.supplier_add');
    }

    public function SupplierStore(Request $request) {
        Supplier::insert([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_no' => $request->mobile_no,
            'address' => $request->address,
            'created_by' => auth()->user()->id,
            'created_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'Supplier Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('supplier.all')->with($notification);
    }

    public function SupplierEdit($id) {
        $supplier = Supplier::findOrFail($id);
        return view('backend.supplier.supplier_edit', compact('supplier'));
    }

    public function SupplierUpdate(Request $request){

        $sullier_id = $request->id;

        Supplier::findOrFail($sullier_id)->update([
            'name' => $request->name,
            'mobile_no' => $request->mobile_no,
            'email' => $request->email,
            'address' => $request->address,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'Supplier Updated Successfully',
            'alert-type' => 'info'
        );

        return redirect()->route('supplier.all')->with($notification);

    }

    public function SupplierDelete($id){

        Supplier::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Supplier has been deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }
}
