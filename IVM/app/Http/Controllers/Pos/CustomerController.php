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
    public function CustomerAll()
    {

        $customers = Customer::latest()->get();
        return view('backend.customer.customer_all', compact('customers'));
    }

    public function CustomerAdd(){
        return view('backend.customer.customer_add');
    }

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

    public function CustomerEdit($id){

        $customer = Customer::findOrFail($id);
        return view('backend.customer.customer_edit',compact('customer'));

    }

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
