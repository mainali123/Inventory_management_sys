<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Auth;
use Illuminate\Support\Carbon;


class CategoryController extends Controller
{

    /*
    @Author: Adhiraj Lamichhane
    The below function displays all categories in the backend.
    @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View -> returns the view of all categories
    @throws \Throwable -> throws an exception if the view cannot be loaded

    Code Reference:
    1.https://laravel.com/docs/10.x/eloquent#retrieving-models
    2.https://laravel.com/docs/10.x/queries#ordering-grouping-limit-and-offset
     */
    public function CategoryAll(){

        $categoris = Category::latest()->get();
        return view('backend.category.category_all',compact('categoris'));

    } // End Mehtod


    /*
    @Author: Adhiraj Lamichhane
    The below function displays the form for adding a new category in the backend.
    @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View -> returns the view of the form for adding a new category
    @throws \Throwable -> throws an exception if the view cannot be loaded

    Code Reference:
    1.https://laravel.com/docs/10.x/views#creating-views
     */
    public function CategoryAdd(){
        return view('backend.category.category_add');
    } // End Mehtod


    /*
    @Author: Adhiraj Lamichhane
    The below function store a new category in the backend.
    @param Request  $request -> the request object that contains the data that is sent to the server
    @return \Illuminate\Http\RedirectResponse -> returns the view of all categories

    Code Reference:
    1.https://laravel.com/docs/10.x/requests
    2.https://laravel.com/docs/10.x/authentication
    3.https://carbon.nesbot.com/docs/
    4.https://laravel.com/api/10.x/Illuminate/Http/RedirectResponse.html
     */
    public function CategoryStore(Request $request){

        Category::insert([
            'name' => $request->name,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'Category Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('category.all')->with($notification);

    } // End Method

    /*
    @Author: Adhiraj Lamichhane
    The below function displays the edit form for a category in the backend
    @param int $id -> The ID of the category to edit
    @return \Illuminate\View\View -> returns the view of the edit form for a category

    Code Reference:
    1.https://laravel.com/docs/10.x/eloquent-relationships#retrieving-single-models
    2.https://laravel.com/api/10.x/Illuminate/View/View.html
     */
    public function CategoryEdit($id){

        $category = Category::findOrFail($id);
        return view('backend.category.category_edit',compact('category'));

    }// End Method


    /*
    @Author: Adhiraj Lamichhane
    The below function updates a category in the backend
    @param Request $request The incoming HTTP request
    @return \Illuminate\Http\RedirectResponse -> returns the view of all categories

    Code Reference:
    1.https://laravel.com/docs/10.x/requests#retrieving-input
    2.https://laravel.com/docs/10.x/eloquent#updates
    3.https://laravel.com/docs/10.x/helpers#method-redirect
     */
    public function CategoryUpdate(Request $request){

        $category_id = $request->id;

        Category::findOrFail($category_id)->update([
            'name' => $request->name,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'Category Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('category.all')->with($notification);

    }// End Method


    /*
    @Author: Adhiraj Lamichhane
    The below function deletes a category from the backend
    @param int $id -> The ID of the category to delete
    @return \Illuminate\Http\RedirectResponse -> returns the view of all categories

    Code Reference:
    1.https://laravel.com/docs/10.x/eloquent#deleting-models
    2.https://laravel.com/docs/10.x/helpers#method-redirect
     */
    public function CategoryDelete($id){

        Category::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // End Method


}
