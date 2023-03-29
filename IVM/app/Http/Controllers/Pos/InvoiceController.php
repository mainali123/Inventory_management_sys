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
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\Customer;
use DB;

class InvoiceController extends Controller
{

    /*
    @Author: Diwash Mainali
    This function retrieves all invoices and renders the 'backend.invoice.invoice_all' view with the data
    @return \Illuminate\View\View -> The view instance for the 'backend.invoice.invoice_all' view

    Code Reference:
    1. https://laravel.com/docs/10.x/blade#displaying-data
     */
    public function InvoiceAll(){
        $allData = Invoice::orderBy('date','desc')->orderBy('id','desc')->where('status','1')->get();
        return view('backend.invoice.invoice_all',compact('allData'));
    }


    /*
    @Author: Diwash Mainali
    This function displays the form to add a new invoice, with options to select a customer, category, and generate a unique invoice number based on the last invoice record in the database
    @return \Illuminate\View\View -> The HTML view to display the invoice form

    Code Reference:
    1. https://laravel.com/docs/10.x/blade#displaying-data
    2. https://laravel.com/docs/10.x/eloquent#retrieving-models
     */
    public function invoiceAdd(){
        $costomer = Customer::all();
        $category = Category::all();
        $invoice_data = Invoice::orderBy('id','desc')->first();
        if ($invoice_data == null) {
            $firstReg = '0';
            $invoice_no = $firstReg+1;
        }else{
            $invoice_data = Invoice::orderBy('id','desc')->first()->invoice_no;
            $invoice_no = $invoice_data+1;
        }
        $date = date('Y-m-d');
        return view('backend.invoice.invoice_add',compact('invoice_no','category','date','costomer'));
    }

    /*
    @Author: Diwash Mainali
    This function handles the submission of a new invoice to the database, along with its related details, customer, and payment information.
    @param Request $request -> The incoming request object containing the form data
    @return \Illuminate\Http\RedirectResponse -> A redirect response to the invoice pending list page, with a corresponding flash message indicating whether the insertion was successful or not

    Code Reference:
    1. https://laravel.com/docs/10.x/eloquent#inserts
    2. https://laravel.com/docs/10.x/eloquent#mass-assignment
    3. https://laravel.com/docs/10.x/database#database-transactions
    4. https://laravel.com/docs/10.x/notifications#formatting-notifications
     */

    public function InvoiceStore(Request $request){

        if ($request->category_id == null) {

            $notification = array(
                'message' => 'Sorry You do not select any item',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);

        } else{
            if ($request->paid_amount > $request->estimated_amount) {

                $notification = array(
                    'message' => 'Sorry Paid Amount is Maximum the total price',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);

            } else {

                $invoice = new Invoice();
                $invoice->invoice_no = $request->invoice_no;
                $invoice->date = date('Y-m-d',strtotime($request->date));
                $invoice->description = $request->description;
                $invoice->status = '0';
                $invoice->created_by = Auth::user()->id;

                DB::transaction(function() use($request,$invoice){
                    if ($invoice->save()) {
                        $count_category = count($request->category_id);
                        for ($i=0; $i < $count_category ; $i++) {

                            $invoice_details = new InvoiceDetail();
                            $invoice_details->date = date('Y-m-d',strtotime($request->date));
                            $invoice_details->invoice_id = $invoice->id;
                            $invoice_details->category_id = $request->category_id[$i];
                            $invoice_details->product_id = $request->product_id[$i];
                            $invoice_details->selling_qty = $request->selling_qty[$i];
                            $invoice_details->unit_price = $request->unit_price[$i];
                            $invoice_details->selling_price = $request->selling_price[$i];
                            $invoice_details->status = '1';
                            $invoice_details->save();
                        }

                        if ($request->customer_id == '0') {
                            $customer = new Customer();
                            $customer->name = $request->name;
                            $customer->mobile_no = $request->mobile_no;
                            $customer->email = $request->email;
                            $customer->save();
                            $customer_id = $customer->id;
                        } else{
                            $customer_id = $request->customer_id;
                        }

                        $payment = new Payment();
                        $payment_details = new PaymentDetail();

                        $payment->invoice_id = $invoice->id;
                        $payment->customer_id = $customer_id;
                        $payment->paid_status = $request->paid_status;
                        $payment->discount_amount = $request->discount_amount;
                        $payment->total_amount = $request->estimated_amount;

                        if ($request->paid_status == 'full_paid') {
                            $payment->paid_amount = $request->estimated_amount;
                            $payment->due_amount = '0';
                            $payment_details->current_paid_amount = $request->estimated_amount;
                        } elseif ($request->paid_status == 'full_due') {
                            $payment->paid_amount = '0';
                            $payment->due_amount = $request->estimated_amount;
                            $payment_details->current_paid_amount = '0';
                        }elseif ($request->paid_status == 'partial_paid') {
                            $payment->paid_amount = $request->paid_amount;
                            $payment->due_amount = $request->estimated_amount - $request->paid_amount;
                            $payment_details->current_paid_amount = $request->paid_amount;
                        }
                        $payment->save();

                        $payment_details->invoice_id = $invoice->id;
                        $payment_details->date = date('Y-m-d',strtotime($request->date));
                        $payment_details->save();
                    }

                });

            } // end else
        }

        $notification = array(
            'message' => 'Invoice Data Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('invoice.pending.list')->with($notification);
    }

    /*
    @Author: Diwash Mainali
    This function handles the retrieval of all pending invoices from the database and returns them to the invoice pending list view.
    @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View -> The invoice pending list view, with the corresponding data

    Code Reference:
    1. https://laravel.com/docs/10.x/eloquent#retrieving-models
    2. https://laravel.com/docs/10.x/eloquent#ordering
     */
    public function PendingList(){
        $allData = Invoice::orderBy('date','desc')->orderBy('id','desc')->where('status','0')->get();
        return view('backend.invoice.invoice_pending_list',compact('allData'));
    }

    /*
    @Author: Diwash Mainali
    This function deletes an invoice and its related records from the database
    @param $id -> The id of the invoice to be deleted
    @return \Illuminate\Http\RedirectResponse -> Redirects back to the invoice pending list view, with a notification

    Code Reference (for deleting related records):
    1. https://laravel.com/docs/10.x/eloquent#deleting-models
     */
    public function InvoiceDelete($id){

        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        InvoiceDetail::where('invoice_id',$invoice->id)->delete();
        Payment::where('invoice_id',$invoice->id)->delete();
        PaymentDetail::where('invoice_id',$invoice->id)->delete();

        $notification = array(
            'message' => 'Invoice Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    /*
    @Author: Diwash Mainali
    This function handles the retrieval of all approved invoices from the database and returns them to the invoice approved list view.
    @param $id -> The id of the invoice to be approved
    @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View -> The invoice approved list view, with the corresponding data

    Code Reference:
    1. https://laravel.com/docs/10.x/eloquent#retrieving-models
    2. https://laravel.com/docs/10.x/eloquent#ordering
     */
    public function InvoiceApprove($id){
        $invoice = Invoice::with('invoice_details')->findOrFail($id);
        return view('backend.invoice.invoice_approve',compact('invoice'));
    }

    /*
    @Author: Diwash Mainali
    This function handles the approval of an invoice and its related records from the database and returns them to the invoice approved list view
    @param $id -> The id of the invoice to be approved
    @param Request $request -> The request object containing the data to be approved
    @return \Illuminate\Http\RedirectResponse -> Redirects back to the invoice approved list view, with a notification

    Code Reference:
    1. https://laravel.com/docs/10.x/eloquent#retrieving-models
    2. https://laravel.com/docs/10.x/eloquent#ordering
    3. https://laravel.com/docs/10.x/eloquent#updating-models
    4. https://laravel.com/docs/10.x/database#database-transactions
     */
    public function ApprovalStore(Request $request, $id){
        foreach($request->selling_qty as $key => $val){
            $invoice_details = InvoiceDetail::where('id',$key)->first();
            $product = Product::where('id',$invoice_details->product_id)->first();
            if($product->quantity < $request->selling_qty[$key]){

                $notification = array(
                    'message' => 'Sorry there is insufficient stock.',
                    'alert-type' => 'error'
                );

                return redirect()->back()->with($notification);

            }
        }

        $invoice = Invoice::findOrFail($id);
        $invoice->updated_by = Auth::user()->id;
        $invoice->status = '1';

        DB::transaction(function() use($request,$invoice,$id){
            foreach($request->selling_qty as $key => $val){
                $invoice_details = InvoiceDetail::where('id',$key)->first();
                $product = Product::where('id',$invoice_details->product_id)->first();
                $product->quantity = ((float)$product->quantity) - ((float)$request->selling_qty[$key]);
                $product->save();
            } // end foreach

            $invoice->save();
        });

        $notification = array(
            'message' => 'Invoice Approved Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('invoice.pending.list')->with($notification);
    }

    /*
    @Author: Diwash Mainali
    This function handles the retrieval of all approved invoices from the database and returns them to the invoice approved list view.
    @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View -> The invoice approved list view, with the corresponding data

    Code Reference:
    1. https://laravel.com/docs/10.x/eloquent#retrieving-models
    2. https://laravel.com/docs/10.x/eloquent#ordering
    3. https://laravel.com/docs/10.x/database#database-transactions
     */
    public function PrintInvoiceList(){

        $allData = Invoice::orderBy('date','desc')->orderBy('id','desc')->where('status','1')->get();
        return view('backend.invoice.print_invoice_list',compact('allData'));
    }

    /*
    @Author: Diwash Mainali
    This function handles the retrieval of all approved invoices from the database and returns them to the invoice approved list view.
     */
    public function PrintInvoice($id){
        $invoice = Invoice::with('invoice_details')->findOrFail($id);
        return view('backend.pdf.invoice_pdf',compact('invoice'));

    }

    /*
    @Author: Diwash Mainali
    The function returns the daily invoice report view to the user with the corresponding data from the database
    @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View -> The daily invoice report view, with the corresponding data

    Code Reference:
    1. https://laravel.com/docs/10.x/eloquent#retrieving-models
     */
    public function DailyInvoiceReport(){
        return view('backend.invoice.daily_invoice_report');
    } // End Method

    /*
    @Author: Diwash Mainali
    The function returns the daily invoice report view to the user with the corresponding data from the database
    @param Request $request -> The request object
    @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View -> The daily invoice report view, with the corresponding data

    Code Reference:
    1. https://laravel.com/docs/10.x/eloquent#retrieving-models
    2. https://laravel.com/docs/10.x/eloquent#ordering
    3. https://www.php.net/manual/en/function.date.php
    4. https://www.php.net/manual/en/function.strtotime.php
    5. https://laravel.com/docs/10.x/queries#where-clauses
     */
    public function DailyInvoicePdf(Request $request){

        $sdate = date('Y-m-d',strtotime($request->start_date));
        $edate = date('Y-m-d',strtotime($request->end_date));
        $allData = Invoice::whereBetween('date',[$sdate,$edate])->where('status','1')->get();


        $start_date = date('Y-m-d',strtotime($request->start_date));
        $end_date = date('Y-m-d',strtotime($request->end_date));
        return view('backend.pdf.daily_invoice_report_pdf',compact('allData','start_date','end_date'));
    } // End Method

}
