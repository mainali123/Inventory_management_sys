<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Auth;
use Carbon\Carbon;
use Image;
use App\Models\Payment;
use App\Models\PaymentDetail;


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

    public function CreditCustomer(){

        $allData = Payment::whereIn('paid_status',['full_due','partial_paid'])->get();
        return view('backend.customer.customer_credit',compact('allData'));

    }

    public function CreditCustomerPrintPdf(){

        $allData = Payment::whereIn('paid_status',['full_due','partial_paid'])->get();
        return view('backend.pdf.customer_credit_pdf',compact('allData'));

    }

    public function CustomerEditInvoice($invoice_id){

        $payment = Payment::where('invoice_id',$invoice_id)->first();
        return view('backend.customer.edit_customer_invoice',compact('payment'));

    }

    public function CustomerUpdateInvoice(Request $request,$invoice_id){

        if ($request->new_paid_amount < $request->paid_amount) {

            $notification = array(
                'message' => 'Sorry You Paid Maximum Value',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        } else{
            $payment = Payment::where('invoice_id',$invoice_id)->first();
            $payment_details = new PaymentDetail();
            $payment->paid_status = $request->paid_status;

            if ($request->paid_status == 'full_paid') {
                $payment->paid_amount = Payment::where('invoice_id',$invoice_id)->first()['paid_amount']+$request->new_paid_amount;
                $payment->due_amount = '0';
                $payment_details->current_paid_amount = $request->new_paid_amount;

            } elseif ($request->paid_status == 'partial_paid') {
                $payment->paid_amount = Payment::where('invoice_id',$invoice_id)->first()['paid_amount']+$request->paid_amount;
                $payment->due_amount = Payment::where('invoice_id',$invoice_id)->first()['due_amount']-$request->paid_amount;
                $payment_details->current_paid_amount = $request->paid_amount;

            }

            $payment->save();
            $payment_details->invoice_id = $invoice_id;
            $payment_details->date = date('Y-m-d',strtotime($request->date));
            $payment_details->updated_by = Auth::user()->id;
            $payment_details->save();

            $notification = array(
                'message' => 'Invoice Update Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('credit.customer')->with($notification);
        }
    }

    public function CustomerInvoiceDetails($invoice_id){

        $payment = Payment::where('invoice_id',$invoice_id)->first();
        return view('backend.pdf.invoice_details_pdf',compact('payment'));

    }
}
