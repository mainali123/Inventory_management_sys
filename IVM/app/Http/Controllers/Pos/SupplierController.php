<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Auth;

class SupplierController extends Controller
{

    /*
    @Author: Diwash Mainali
    This function retrieves all suppliers and renders the 'backend.supplier.supplier_all' view with the data
    @return \Illuminate\View\View -> The view instance for the 'backend.supplier.supplier_all' view

    Code Reference:
    1. https://laravel.com/docs/10.x/blade#displaying-data
    */
    public function SupplierAll() {
        $suppliers = Supplier::latest()->get();
        return view('backend.supplier.supplier_all', compact('suppliers'));
    }

    /*
    @Author: Diwash Mainali
    This function displays the form to add a new supplier in the backend
    @return \Illuminate\View\View -> The view instance for the 'backend.supplier.supplier_add' view

    Code Reference:
    1. https://laravel.com/docs/10.x/blade#displaying-dataq
    */
    public function SupplierAdd() {
        return view('backend.supplier.supplier_add');
    }


    /*
    @Author: Diwash Mainali
    This function stores a new supplier in the database based on the given request data and redirects the user to the supplier list view with a success message
    @param Request $request -> The HTTP request containing the supplier data
    @return \Illuminate\Http\RedirectResponse -> The redirect response instance for the supplier list view

    Code Reference:
    1. https://laravel.com/docs/10.x/eloquent#inserts
    */
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

    /*
    @Author: Diwash Mainali
    This function retrieves the supplier data for the given supplier id and renders the 'backend.supplier.supplier_edit' view with the data
    @param int $id -> The id of the supplier to be edited
    @return \Illuminate\View\View -> The view instance for the 'backend.supplier.supplier_edit' view

    Code Reference:
    1. https://laravel.com/docs/10.x/eloquent#retrieving-single-models
    */
    public function SupplierEdit($id) {
        $supplier = Supplier::findOrFail($id);
        return view('backend.supplier.supplier_edit', compact('supplier'));
    }

    /*
    @Author: Diwash Mainali
    This function updates the supplier data for the given supplier id based on the given request data and redirects the user to the supplier list view with a success message
    @param Request $request -> The HTTP request containing the supplier data
    @return \Illuminate\Http\RedirectResponse -> The redirect response instance for the supplier list view

    Code Reference:
    1. https://laravel.com/docs/10.x/eloquent#updates
    */
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

    /*
    @Author: Diwash Mainali
    This function deletes the supplier data for the given supplier id and redirects the user to the supplier list view with a success message
    @param int $id -> The id of the supplier to be deleted
    @return \Illuminate\Http\RedirectResponse -> The redirect response instance for the supplier list view

    Code Reference:
    1. https://laravel.com/docs/10.x/eloquent#deleting-models
    */
    public function SupplierDelete($id){

        Supplier::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Supplier has been deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }
}
