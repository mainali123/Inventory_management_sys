<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Auth;
use Carbon\Carbon;
use Image;


class CustomerController extends Controller
{

    /*
    @Author: Diwash Mainali
    This function retrieves all customers and renders the 'backend.customer.customer_all' view with the data
    @return \Illuminate\View\View -> The view instance for the 'backend.customer.customer_all' view

    Code Reference:
    1. https://laravel.com/docs/10.x/blade#displaying-data
    */
    public function CustomerAll(){
        $customers = Customer::latest()->get();
        return view('backend.customer.customer_all', compact('customers'));
    }

    /*
    @Author: Diwash Mainali
    This function displays the form to add a new customer in the backend
    @return \Illuminate\View\View -> The view instance for the 'backend.customer.customer_add' view

    Code Reference:
    1. https://laravel.com/docs/10.x/blade#displaying-data
    */
    public function CustomerAdd(){
        return view('backend.customer.customer_add');
    }

    /*
    @Author: Diwash Mainali
    This function stores a new customer in the database based on the given request data and redirects the user to the customer list view with a success message
    @param Request $request -> The HTTP request containing the customer data
    @return \Illuminate\Http\RedirectResponse -> The redirect response instance for the customer list view

    Code Reference:
    1. https://laravel.com/docs/10.x/eloquent#inserts
    2. https://laravel.com/docs/10.x/requests#files
    3. https://laravel.com/docs/10.x/eloquent#inserts
    4. https://laravel.com/docs/10.x/eloquent#mass-assignment
     */
    public function CustomerStore(Request $request){
        $image = $request->file('customer_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension(); //hexdec(uniqid()) is used to generate unique id
        Image::make($image)->resize(200,200)->save('upload/customer/'.$name_gen);
        $save_url = 'upload/customer/'.$name_gen;

        Customer::insert([
            'name' => $request->name,
            'mobile_no' => $request->mobile_no,
            'email' => $request->email,
            'address' => $request->address,
            'customer_image' => $save_url ,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'Customer Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('customer.all')->with($notification);
    }

    /*
    @Author: Diwash Mainali
    This function displays the form to edit a customer in the backend
    @param int $id -> The id of the customer to be edited
    @return \Illuminate\View\View -> The view instance for the 'backend.customer.customer_edit' view

    Code Reference:
    1. https://laravel.com/docs/10.x/eloquent#retrieving-single-models
    2. https://laravel.com/docs/10.x/blade#displaying-data
     */
    public function CustomerEdit($id){
        $customer = Customer::findOrFail($id);
        return view('backend.customer.customer_edit',compact('customer'));
    }

    /*
    @Author: Diwash Mainali
    This function updates a customer in the database based on the given request data, including the customer's image if
    provided and redirects the user to the customer list view with a success message
    @param Request $request -> The HTTP request containing the updated customer data
    @return \Illuminate\Http\RedirectResponse -> The HTTP response to redirect to the list of all customers
    @throws \Illuminate\Database\Eloquent\ModelNotFoundException -> If the customer with the given ID is not found

    Code Reference:
    1. https://laravel.com/docs/10.x/eloquent#retrieving-single-models
    2. https://laravel.com/docs/10.x/eloquent#mass-assignment
    3. https://laravel.com/docs/10.x/eloquent#inserts
    4. https://laravel.com/docs/10.x/requests#files
     */
    public function CustomerUpdate(Request $request){

        $customer_id = $request->id;
        if ($request->file('customer_image')) {

            $image = $request->file('customer_image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension(); //hexdec(uniqid()) is used to generate unique id
            Image::make($image)->resize(200,200)->save('upload/customer/'.$name_gen);
            $save_url = 'upload/customer/'.$name_gen;

            Customer::findOrFail($customer_id)->update([
                'name' => $request->name,
                'mobile_no' => $request->mobile_no,
                'email' => $request->email,
                'address' => $request->address,
                'customer_image' => $save_url ,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),

            ]);

            $notification = array(
                'message' => 'Customer Updated with Image Successfully',
                'alert-type' => 'info'
            );

            return redirect()->route('customer.all')->with($notification);

        } else{

            Customer::findOrFail($customer_id)->update([
                'name' => $request->name,
                'mobile_no' => $request->mobile_no,
                'email' => $request->email,
                'address' => $request->address,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),

            ]);

            $notification = array(
                'message' => 'Customer Updated without Image Successfully',
                'alert-type' => 'info'
            );

            return redirect()->route('customer.all')->with($notification);

        }

    }


    /*
    @Author: Diwash Mainali
    This function deletes a customer from the database based on the given ID and redirects the user to the customer list view with a success message
    @param int $id -> The ID of the customer to be deleted
    @return \Illuminate\Http\RedirectResponse -> The HTTP response to redirect to the list of all customers

    Code Reference:
    1. https://laravel.com/docs/10.x/eloquent#retrieving-single-models
    2. https://laravel.com/docs/10.x/eloquent#deleting-models
     */
    public function CustomerDelete($id){

        $customers = Customer::findOrFail($id);
        $img = $customers->customer_image;
        unlink($img);

        Customer::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Customer Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }
}
