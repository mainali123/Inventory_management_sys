<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/*
@author: Adhiraj Lamichhane
    The model to create the 'purchases' table in the database.
    This table is used to store the purchases of the application.
    This model is automatically generated by the IVM framework.

    Code Reference:
    https://laravel.com/docs/10.x/eloquent
*/


class Purchase extends Model
{
    use HasFactory;
    protected $guarded = []; // prevent mass assignment.

    // define the relationship between the purchase and the product.
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }

    // define the relationship between the purchase and the supplier.
    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }

    // define the relationship between the purchase and the unit.
    public function unit(){
        return $this->belongsTo(Unit::class,'unit_id','id');
    }

    // define the relationship between the purchase and the category.
    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }


}
