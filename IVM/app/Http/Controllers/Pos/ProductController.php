<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Unit;
use Auth;
use Illuminate\Support\Carbon;

class ProductController extends Controller
{

    /*
    @Author: Adhiraj Lamichhane
    This function is used to display a listing of all products
    @return \Illuminate\Http\Response -> The view containing the list of products

    Code Reference:
    1.https://laravel.com/docs/10.x/eloquent#retrieving-all-models
    2.https://laravel.com/docs/10.x/queries#ordering-grouping-limit-and-offset
     */
    public function ProductAll(){

        $product = Product::latest()->get();
        return view('backend.product.product_all',compact('product'));

    } // End Method


    /*
    @Author: Adhiraj Lamichhane
    This function is used to show the form for creating a new product
    @return \Illuminate\Http\Response -> The view containing the form for creating a new product

    Code Reference:
    1.https://laravel.com/docs/10.x/eloquent#retrieving-all-models
    2.https://laravel.com/docs/10.x/views#passing-data-to-views
     */
    public function ProductAdd(){

        $supplier = Supplier::all();
        $category = Category::all();
        $unit = Unit::all();
        return view('backend.product.product_add',compact('supplier','category','unit'));
    } // End Method

    /*
    @Author: Adhiraj Lamichhane
    This function is used to store a newly created product in the storage.
    @param \Illuminate\Http\Request $request -> The HTTP request object containing the data of the new product
    @return \Illuminate\Http\Response -> The view containing the list of products

    Code Reference:
    1.https://laravel.com/docs/10.x/eloquent#inserts
    2.https://laravel.com/docs/8.x/queries#inserts
    3.https://laravel.com/docs/10.x/eloquent#mass-assignment
     */
    public function ProductStore(Request $request){

        Product::insert([

            'name' => $request->name,
            'supplier_id' => $request->supplier_id,
            'unit_id' => $request->unit_id,
            'category_id' => $request->category_id,
            'quantity' => '0',
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Product Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('product.all')->with($notification);

    } // End Method

    /*
    @Author: Adhiraj Lamichhane
    This function is used to display the specified product
    @param int $id -> The id of the product to be displayed
    @return \Illuminate\Http\Response -> The view containing the details of the specified product

    Code Reference:
    1.https://laravel.com/docs/8.x/controllers#route-model-binding
    2.https://laravel.com/docs/10.x/eloquent#retrieving-single-models
     */

    public function ProductEdit($id){

        $supplier = Supplier::all();
        $category = Category::all();
        $unit = Unit::all();
        $product = Product::findOrFail($id);
        return view('backend.product.product_edit',compact('product','supplier','category','unit'));
    } // End Method


    /*
    @Author: Adhiraj Lamichhane
    This function is used to display a listing of the resource
    @return \Illuminate\Http\Response -> The view containing the list of products

    Code Reference:
    1.https://laravel.com/docs/10.x/views#passing-data-to-views
    2.https://laravel.com/docs/10.x/eloquent#retrieving-single-models
    3.https://laravel.com/docs/10.x/queries
    4.https://laravel.com/docs/10.x/views
     */
    public function ProductUpdate(Request $request){

        $product_id = $request->id;

        Product::findOrFail($product_id)->update([

            'name' => $request->name,
            'supplier_id' => $request->supplier_id,
            'unit_id' => $request->unit_id,
            'category_id' => $request->category_id,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Product Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('product.all')->with($notification);


    } // End Method


    /*
    @Author: Adhiraj Lamichhane
    This function is used to delete a product by ID
    @param int $id -> The id of the product to be deleted
    @return \Illuminate\Http\RedirectResponse -> The view containing the list of products
    @throws \Illuminate\Database\Eloquent\ModelNotFoundException -> If the product with the specified ID is not found

    Code Reference:
    1.https://laravel.com/docs/10.x/database
    2.https://laravel.com/docs/10.x/controllers
    3.https://laravel.com/docs/10.x/redirects
     */
    public function ProductDelete($id){

        Product::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Product Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // End Method



}
