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

class DefaultController extends Controller
{
    /*
    @Author: Diwash Mainali
    @Author: Adhiraj Lamichhane
    This function is used to get the data of the supplier, category, product, and stock. It is used to populate the dropdowns in the invoice page
    @param Request $request -> This is used to get the data from the request
    @return \Illuminate\Http\JsonResponse -> The JSON response containing the retrieved data

    Code Reference:
    1. https://laravel.com/docs/10.x/responses#json-responses
     */
    public function GetCategory(Request $request){

        $supplier_id = $request->supplier_id;
        $allCategory = Product::with(['category'])->select('category_id')->where('supplier_id',$supplier_id)->groupBy('category_id')->get();
        return response()->json($allCategory);
    }


    /*
    @Author: Diwash Mainali
    @Author: Adhiraj Lamichhane
    This function retrieves all products in a specified category using the Eloquent model
    @param Request $request -> The HTTP request object containing the category ID
    @return \Illuminate\Http\JsonResponse -> The JSON response containing the retrieved products

    Code Reference:
    1. https://laravel.com/docs/10.x/responses#json-responses
     */
    public function GetProduct(Request $request){

        $category_id = $request->category_id;
        $allProduct = Product::where('category_id',$category_id)->get();
        return response()->json($allProduct);
    }

    /*
    @Author: Diwash Mainali
    This function retrieves the stock of a product using the Eloquent model and returns it as a JSON response
    @param Request $request -> The HTTP request object containing the product ID
    @return \Illuminate\Http\JsonResponse -> The JSON response containing the retrieved stock

    Code Reference:
    1. https://laravel.com/docs/10.x/responses#json-responses
    2. https://laravel.com/docs/10.x/eloquent#retrieving-single-models
    3. https://laravel.com/docs/10.x/eloquent#retrieving-aggregates
    4. https://laravel.com/docs/10.x/eloquent#retrieving-results
     */
    public function GetStock(Request $request){
        $product_id = $request->product_id;
        $stock = Product::where('id',$product_id)->first()->quantity;
        return response()->json($stock);

    }
}
