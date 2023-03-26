<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /*
    @Author: Diwash Mainali
    Log out the authenticated user, invalidate the session, regenerate the CSRF token, and redirect the user to the login page with a success message
    @param Request $request -> The HTTP request instance
    @return RedirectResponse -> The HTTP response instance

    About CSRF token:
    CSRF stands for Cross-Site Request Forgery, which is a type of attack where a malicious website or application tricks a user into executing an action on another website without their knowledge or consent.
    The CSRF token is generated for the user's session, ensuring that any subsequent form or AJAX requests from the user will use a new, unique token. This helps to prevent CSRF attacks by making it much more difficult for attackers to generate valid tokens and execute unauthorized actions on behalf of the user.
    For more information, please refer to the Laravel documentation: https://laravel.com/docs/10.x/csrf#csrf-x-csrf-token

    Code Reference:
    1. https://laravel.com/docs/10.x/authentication#logging-out
    2. https://laravel.com/docs/10.x/csrf#csrf-x-csrf-token
    */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken(); // regenerates the CSRF token for the user's session

        $notification = array(
            'message' => 'Logout successfully',
            'alert-type' => 'success'
        );

        return redirect('/login')->with($notification);
    }


    /*
     @Author: Diwash Mainali
    This function is used to display the current authenticated user's profile data on the admin profile view
    @return \Illuminate\View\View -> The view instance for the admin profile view

    Code Reference:
    1. https://laravel.com/docs/10.x/authentication#retrieving-the-authenticated-user
     */
    public function Profile(){
        $userId = Auth::user()->id; // get current user id
        $admin_Data = User::find($userId);  // get current user data
        return view('admin.admin_profile_view', compact('admin_Data')); // passing data to admin profile view
    }


    /*
    @Author: Diwash Mainali
    This function is used to Display the current authenticated user's data on the admin profile edit view for editing
    @return \Illuminate\View\View -> The view instance for the admin profile edit view

    Code Reference:
    1. https://laravel.com/docs/10.x/authentication#retrieving-the-authenticated-user
    */
    public function EditProfile(){
        $userId = Auth::user()->id; // get current user id
        $edit_admin_Data = User::find($userId);  // get current user data
        return view('admin.admin_profile_edit', compact('edit_admin_Data')); // passing data to admin profile view
    }


    /*
    @Author: Diwash Mainali
    This function is used to update the current authenticated user's profile data with the values submitted through the HTTP request, including the user's name, email, and profile picture (if provided), and redirect the user to the admin profile view with a success message
    @param Request $request -> The HTTP request instance containing the submitted form data
    @return \Illuminate\Http\RedirectResponse -> The redirect response instance for the admin profile view

    Code Reference:
    1. https://laravel.com/docs/10.x/eloquent#updating-models
    */
    public function StoreProfile(Request $request) {
        $userId = Auth::user()->id; // get current user id
        $edit_admin_Data = User::find($userId);  // get current user data
        $edit_admin_Data->name = $request->name;
        $edit_admin_Data->email = $request->email;

        if($request->hasFile('profile_picture')){
            $file = $request->file('profile_picture');
            @unlink(public_path('upload/admin_images/'.$edit_admin_Data->profile_photo_path));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $filename);
            $edit_admin_Data['profile_picture'] = $filename;
        }
        $edit_admin_Data->save();


        $notification = array(
            'message' => 'Profile updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.profile')->with($notification);
    }


    /*
    @Author: Diwash Mainali
    This function is used to render the view for the admin user to change their password with the current password, new password, and confirm password fields
    @return \Illuminate\Contracts\View\View -> The view instance for the admin change password view

    Code Reference:
    1. https://laravel.com/docs/10.x/authentication#retrieving-the-authenticated-user
    */
    public function ChangePassword(){
        return view('admin.admin_change_password');
    }


    /*
    @Author: Diwash Mainali
    This function is used to handles the updating of the current user's password with the values submitted through the HTTP request, including the user's old password, new password, and confirm password, and redirect the user to the admin profile view with a success message
    @param \Illuminate\Http\Request $request -> The HTTP request instance containing the submitted form data
    @return \Illuminate\Http\RedirectResponse -> The redirect response instance for the admin profile view

    Code Reference:
    1. https://laravel.com/docs/10.x/eloquent#updating-models
    */
    public function UpdatePassword(Request $request){

        $validateData = $request->validate([
            'oldpassword' => 'required',
            'newpassword' => 'required',
            'confirmpassword' => 'required|same:newpassword'
        ]);

        $hashedPassword = auth()->user()->password;
        if(Hash::check($request->oldpassword, $hashedPassword)){
            $user = User::find(Auth::id());
            $user->password = bcrypt($request->newpassword);
            $user->save();

            session()->flash('message', 'Password changed successfully');
            return redirect()->route('admin.profile');
        } else {
            session()->flash('message', 'Old password does not match');
            return redirect()->back();
        }
    }

}
