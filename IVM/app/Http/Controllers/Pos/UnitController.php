<?php

namespace App\Http\Controllers\Pos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use Auth;
use Illuminate\Support\Carbon;
class UnitController extends Controller
{
    /*
    @Author: Adhiraj Lamichhane
    The below function is used to display a listing of all units in the backend
    @return \Illuminate\View\View -> returns the view of all units

    Code Reference:
    1.https://laravel.com/docs/10.x/queries#retrieving-all-results
    2.https://laravel.com/docs/10.x/views#creating-views
     */
    public function UnitAll(){

        $units = Unit::latest()->get();
        return view('backend.unit.unit_all',compact('units'));
    } // End Method

    /*
    @Author: Adhiraj Lamichhane
    The below function is used to display the form for creating a new unit in the backend
    @return \Illuminate\View\View -> returns the view of the form for creating a new unit

    Code Reference:
    1.https://laravel.com/docs/10.x/views#creating-views
     */
    public function UnitAdd(){
        return view('backend.unit.unit_add');
    } // End Method

    /*
    @Author: Adhiraj Lamichhane
    The below function is used to store a newly created unit in the database
    @param Request $request -> contains the data that is sent to the server
    @return \Illuminate\Http\RedirectResponse -> redirects the user to the view of all units

    Code Reference:
    1.https://laravel.com/docs/10.x/queries#inserts
    2.https://laravel.com/docs/10.x/authentication#retrieving-the-authenticated-user
    3.https://carbon.nesbot.com/docs/
    4.https://laravel.com/docs/10.x/redirects#redirecting-with-flashed-session-data
     */
    public function UnitStore(Request $request){
        Unit::insert([
            'name' => $request->name,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
        ]);
        $notification = array(
            'message' => 'Unit Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('unit.all')->with($notification);
    } // End Method


    /*
    @Author: Adhiraj Lamichhane
    The below function is used to display the form for editing the specified resource
    @param  int  $id -> contains the id of the unit that is to be edited
    @return \Illuminate\Http\Response -> returns the view of the form for editing the specified resource

    Code Reference:
    1.https://laravel.com/docs/10.x/eloquent
     */
    public function UnitEdit($id){

        $unit = Unit::findOrFail($id);
        return view('backend.unit.unit_edit',compact('unit'));

    }// End Method


    /*
    @Author: Adhiraj Lamichhane
    The below function is used to update the specified resource in storage
    @param Request $request -> contains the data that is sent to the server
    @return \Illuminate\Http\Response -> redirects the user to the view of all units

    Code Reference:
    1.https://laravel.com/docs/10.x/eloquent#updating-models
    2.https://laravel.com/docs/10.x/authentication#retrieving-the-authenticated-user
    3.https://carbon.nesbot.com/docs/
    4.https://laravel.com/docs/10.x/redirects#redirecting-with-flashed-session-data
     */
    public function UnitUpdate(Request $request){

        $unit_id = $request->id;

        Unit::findOrFail($unit_id)->update([
            'name' => $request->name,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'Unit Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('unit.all')->with($notification);

    }// End Method


    /*
    @Author: Adhiraj Lamichhane
    The below function is used to delete a unit
    @param int $id -> contains the id of the unit that is to be deleted
    @return \Illuminate\Http\RedirectResponse -> redirects the user to the view of all units

    Code Reference:
    1.https://laravel.com/docs/10.x/eloquent#deleting-models
    2.https://laravel.com/docs/10.x/redirects#redirecting-with-flashed-session-data
     */
    public function UnitDelete($id){

        Unit::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Unit Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // End Method



}
