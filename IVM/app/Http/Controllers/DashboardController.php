<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Category;
use Auth;
use Illuminate\Support\Carbon;


class DashboardController extends Controller
{
    //get dashboard data
    public function dashboard()
    {
        $totalproduct = Product::count();
//        $totalpurchase = Purchase::count();
//        $totalsupplier = Supplier::count();
//        $uniqueuser= User::count();

        return view('admin.index', compact('totalproduct'));
    }
}
