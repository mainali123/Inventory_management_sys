<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/*
@author: Diwash Mainali
The model to create the 'payments' table in the database.
This table is used to store the payments of the application.
This model is automatically generated by the IVM framework.

Code Reference:
https://laravel.com/docs/10.x/eloquent
*/

class Payment extends Model
{
    // define the relationship between the payment and the payment detail.
    use HasFactory;
    protected $guarded = []; // This is used to prevent mass assignment.

    public function customer(){
        // define the relationship between the payment and the customer.
        return $this->belongsTo(Customer::class,'customer_id','id');
    }

    public function invoice(){
        return $this->belongsTo(Invoice::class,'invoice_id','id');
    }
}
