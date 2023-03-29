<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pos\SupplierController;
use App\Http\Controllers\Pos\CustomerController;
use App\Http\Controllers\Pos\UnitController;
use App\Http\Controllers\Pos\CategoryController;
use App\Http\Controllers\Pos\ProductController;
use App\Http\Controllers\Pos\InvoiceController;
use App\Http\Controllers\Pos\PurchaseController;
use App\Http\Controllers\Pos\DefaultController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
 * @Author: Diwash Mainali
The admin controller is a controller that is used to manage all the actions of the admin. This is similar to all the other controllers that we have created.

It manages different routes for the admin. A route is a way of mapping an HTTP request to a specific PHP function, called a controller action, that should be executed to handle that request.
A route defines a URL pattern that is associated with a specific controller action, which can then be executed when a user visits that URL.

The controller is grouped together with different routes. This allows the code to be more organized and easier to read.
It also helps to avoid repetition of the same code. Grouping routes together also allows us to apply middleware to all the routes in the group if needed, and avoid naming conflicts.

The reference that we took to learn about route groups is the Laravel documentation. You can find it here: https://laravel.com/docs/10.x/routing#route-groups
*/

// Admin Route
Route::middleware('auth')->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::get('/admin/logout', 'destroy')->name('admin.logout');
        Route::get('/admin/profile', 'Profile')->name('admin.profile');
        Route::get('/edit/profile', 'EditProfile')->name('edit.profile');
        Route::match(['get', 'post'], '/store/profile', 'StoreProfile')->name('store.profile');
        Route::get('/change/password', 'ChangePassword')->name('change.password');
        Route::match(['get', 'post'], '/update/password', 'UpdatePassword')->name('update.password');
    });
});

// Suppliers Route
Route::middleware('auth')->group(function () {
    Route::controller(SupplierController::class)->group(function () {
        Route::get('/supplier/all', 'SupplierAll')->name('supplier.all');
        Route::get('/supplier/add', 'SupplierAdd')->name('supplier.add');
        Route::match(['get', 'post'],'/supplier/store', 'SupplierStore')->name('supplier.store');
        Route::get('/supplier/edit/{id}', 'SupplierEdit')->name('supplier.edit');
        Route::match(['get', 'post'],'/supplier/update', 'SupplierUpdate')->name('supplier.update');
        Route::get('/supplier/delete/{id}', 'SupplierDelete')->name('supplier.delete');
    });
});


// Customers Route
Route::middleware('auth')->group(function () {
    Route::controller(CustomerController::class)->group(function () {
        Route::get('/customer/all', 'CustomerAll')->name('customer.all');
        Route::get('/customer/add', 'CustomerAdd')->name('customer.add');
        Route::match(['get', 'post'],'/customer/store', 'CustomerStore')->name('customer.store');
        Route::get('/customer/edit/{id}', 'CustomerEdit')->name('customer.edit');
        Route::match(['get', 'post'],'/customer/update', 'CustomerUpdate')->name('customer.update');
        Route::get('/customer/delete/{id}', 'CustomerDelete')->name('customer.delete');
    });
});

// Units Route
Route::middleware('auth')->group(function () {
    Route::controller(UnitController::class)->group(function () {
        Route::get('/unit/all', 'UnitAll')->name('unit.all');
        Route::get('/unit/add', 'UnitAdd')->name('unit.add');
        Route::match(['get', 'post'],'/unit/store', 'UnitStore')->name('unit.store');
        Route::get('/unit/edit/{id}', 'UnitEdit')->name('unit.edit');
        Route::match(['get', 'post'],'/unit/update', 'UnitUpdate')->name('unit.update');
        Route::get('/unit/delete/{id}', 'UnitDelete')->name('unit.delete');
    });
});

// Categorys Route
Route::middleware('auth')->group(function () {
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/category/all', 'CategoryAll')->name('category.all');
        Route::get('/category/add', 'CategoryAdd')->name('category.add');
        Route::match(['get', 'post'],'/category/store', 'CategoryStore')->name('category.store');
        Route::get('/category/edit/{id}', 'CategoryEdit')->name('category.edit');
        Route::match(['get', 'post'],'/category/update', 'CategoryUpdate')->name('category.update');
        Route::get('/category/delete/{id}', 'CategoryDelete')->name('category.delete');
    });
});

// Products Route
Route::middleware('auth')->group(function () {
    Route::controller(ProductController::class)->group(function () {
        Route::get('/product/all', 'ProductAll')->name('product.all');
        Route::get('/product/add', 'ProductAdd')->name('product.add');
        Route::match(['get', 'post'],'/product/store', 'ProductStore')->name('product.store');
        Route::get('/product/edit/{id}', 'ProductEdit')->name('product.edit');
        Route::match(['get', 'post'],'/product/update', 'ProductUpdate')->name('product.update');
        Route::get('/product/delete/{id}', 'ProductDelete')->name('product.delete');
    });
});

// Purchases Route
Route::middleware('auth')->group(function () {
    Route::controller(PurchaseController::class)->group(function () {
        Route::get('/purchase/all', 'PurchaseAll')->name('purchase.all');
        Route::get('/purchase/add', 'PurchaseAdd')->name('purchase.add');
        Route::match(['get', 'post'],'/purchase/store', 'PurchaseStore')->name('purchase.store');
        Route::get('/purchase/delete/{id}', 'PurchaseDelete')->name('purchase.delete');
        Route::get('/purchase/pending', 'PurchasePending')->name('purchase.pending');
        Route::get('/purchase/approve/{id}', 'PurchaseApprove')->name('purchase.approve');

    });
});

// Defaults Route
Route::middleware('auth')->group(function () {
    Route::controller(DefaultController::class)->group(function () {
        Route::get('/get-category', 'GetCategory')->name('get-category');
        Route::get('/get-product', 'GetProduct')->name('get-product');
        Route::get('/check-product', 'GetStock')->name('check-product-stock');
    });
});

// Invoices Route
Route::middleware('auth')->group(function () {
    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/invoice/all', 'InvoiceAll')->name('invoice.all');
        Route::get('/invoice/add', 'invoiceAdd')->name('invoice.add');
        Route::match(['get', 'post'],'/invoice/store', 'InvoiceStore')->name('invoice.store');
        Route::get('/invoice/pending/list', 'PendingList')->name('invoice.pending.list');
        Route::get('/invoice/delete/{id}', 'InvoiceDelete')->name('invoice.delete');
        Route::get('/invoice/approve/{id}', 'InvoiceApprove')->name('invoice.approve');
        Route::match(['get', 'post'],'/approval/store/{id}', 'ApprovalStore')->name('approval.store');
        Route::get('/print/invoice/list', 'PrintInvoiceList')->name('print.invoice.list');
        Route::get('/print/invoice/{id}', 'PrintInvoice')->name('print.invoice');
        Route::get('/daily/invoice/report', 'DailyInvoiceReport')->name('daily.invoice.report');
        Route::get('/daily/invoice/pdf', 'DailyInvoicePdf')->name('daily.invoice.pdf');
    });
});

require __DIR__ . '/auth.php';
