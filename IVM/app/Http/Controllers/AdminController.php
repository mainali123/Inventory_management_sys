<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Logout successfully',
            'alert-type' => 'success'
        );

        return redirect('/login')->with($notification);
    }

    public function Profile(){
        $userId = Auth::user()->id; // get current user id
        $admin_Data = User::find($userId);  // get current user data
        return view('admin.admin_profile_view', compact('admin_Data')); // passing data to admin profile view
    }

    public function EditProfile(){
        $userId = Auth::user()->id; // get current user id
        $edit_admin_Data = User::find($userId);  // get current user data
        return view('admin.admin_profile_edit', compact('edit_admin_Data')); // passing data to admin profile view
    }

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

    public function ChangePassword(){
        return view('admin.admin_change_password');
    }

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
