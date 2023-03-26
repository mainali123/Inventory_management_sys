<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Category;
use Auth;
use Illuminate\Support\Carbon;

class PurchaseController extends Controller
{
    /*
    @Author: Adhiraj Lamichhane
    This function retrieves all the purchases made by the user from the Purchase table using Eloquent ORM and displays them in the purchase_all view
    @return \Illuminate\Http\Response -> The response object containing the view and the data to be displayed

    Code Reference:
    1. https://laravel.com/docs/10.x/eloquent#retrieving-models
     */
    public function PurchaseAll(){

        $allData = Purchase::orderBy('date','desc')->orderBy('id','desc')->get();
        return view('backend.purchase.purchase_all',compact('allData'));

    } // End Method

    /*
    @Author: Adhiraj Lamichhane
    This function loads the purchase_add view along with the necessary data from the Supplier, Unit, and Category tables using Eloquent ORM
    @return \Illuminate\Http\Response -> The response object containing purchase_add view with the arrays of all suppliers, units, and categories

    Code Reference:
    1. https://laravel.com/docs/10.x/eloquent#retrieving-models
    2. https://laravel.com/docs/10.x/eloquent#retrieving-all-models
    3. https://laravel.com/docs/10.x/views#passing-data-to-views
     */
    public function PurchaseAdd(){

        $supplier = Supplier::all();
        $unit = Unit::all();
        $category = Category::all();
        return view('backend.purchase.purchase_add',compact('supplier','unit','category'));

    } // End Method


    /*
    @Author: Adhiraj Lamichhane
    This function is used to store purchase information in the database
    @param Request $request -> The HTTP request object containing the data to be stored
    @return \Illuminate\Http\RedirectResponse -> The redirect response object containing the route to redirect to and the notification to be displayed to the user

    Code Reference:
    1.https://laravel.com/docs/10.x/requests
    2.https://laravel.com/docs/10.x/redirects
    3.https://laravel.com/docs/10.x/eloquent#inserting-and-updating-models
     */
    public function PurchaseStore(Request $request){

        if ($request->category_id == null) {

            $notification = array(
                'message' => 'Sorry you do not select any item',
                'alert-type' => 'error'
            );
            return redirect()->back( )->with($notification);
        } else {

            $count_category = count($request->category_id);
            for ($i=0; $i < $count_category; $i++) {
                $purchase = new Purchase();
                $purchase->date = date('Y-m-d', strtotime($request->date[$i]));
                $purchase->purchase_no = $request->purchase_no[$i];
                $purchase->supplier_id = $request->supplier_id[$i];
                $purchase->category_id = $request->category_id[$i];

                $purchase->product_id = $request->product_id[$i];
                $purchase->buying_qty = $request->buying_qty[$i];
                $purchase->unit_price = $request->unit_price[$i];
                $purchase->buying_price = $request->buying_price[$i];
                $purchase->description = $request->description[$i];

                $purchase->created_by = Auth::user()->id;
                $purchase->status = '0';
                $purchase->save();
            } // end foreach
        } // end else

        $notification = array(
            'message' => 'Data Save Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('purchase.all')->with($notification);
    } // End Method


    /*
    @Author: Adhiraj Lamichhane
    This function is used to remove the specified resource from storage
    @param  int  $id -> The id of the resource to be deleted
    @return \Illuminate\Http\Response -> The response object containing the route to redirect to and the notification to be displayed to the user

    Code Reference:
    1.https://laravel.com/docs/10.x/eloquent#deleting-models
    2.https://laravel.com/docs/10.x/eloquent#retrieving-single-models
     */
    public function PurchaseDelete($id){

        Purchase::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Purchase Iteam Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    } // End Method


    /*
    @Author: Adhiraj Lamichhane
    This function is used to display a listing of the pending purchases
    @return \Illuminate\Http\Response -> The response object containing the view and the data to be displayed

    Code Reference:
    1.https://laravel.com/docs/10.x/eloquent#retrieving-models
    2.https://laravel.com/docs/10.x/queries#ordering-grouping-limit-and-offset
     */
    public function PurchasePending(){

        $allData = Purchase::orderBy('date','desc')->orderBy('id','desc')->where('status','0')->get();
        return view('backend.purchase.purchase_pending',compact('allData'));
    }// End Method

    /*
    @Author: Adhiraj Lamichhane
    This function is used to approve the specified purchase and update the status to 1
    @param  int  $id -> The id of the purchase to be approved
    @return \Illuminate\Http\Response -> The response object containing the route to redirect to and the notification to be displayed to the user

    Code Reference:
    1.https://laravel.com/docs/10.x/eloquent#updating-models
    2.https://laravel.com/docs/10.x/queries#retrieving-results
    3.https://laravel.com/docs/10.x/eloquent#retrieving-single-models
    4.https://laravel.com/docs/10.x/eloquent#updating-models
     */
    public function PurchaseApprove($id){

        $purchase = Purchase::findOrFail($id);
        $product = Product::where('id',$purchase->product_id)->first();
        $purchase_qty = ((float)($purchase->buying_qty))+((float)($product->quantity));
        $product->quantity = $purchase_qty;

        if($product->save()){

            Purchase::findOrFail($id)->update([
                'status' => '1',
            ]);

            $notification = array(
                'message' => 'Status Approved Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('purchase.all')->with($notification);

        }

    }// End Method


}
