<?php

namespace App\Http\Controllers;

use App\Models\LogAction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;

class InfoUserController extends Controller
{

    public function userManagement(Request $request){
        $data['page_title'] = 'User Management';
     
        $data['user'] = User::orderBy('id','desc')->get();
      
		return view('account-management/user-management',$data);
    }

    public function create()
    {
        $data['page_title'] = 'Profile '.Auth::user()->name;
        return view('account-management/user-profile',$data);
    }

    public function store(Request $request)
    {

        User::where('id',Auth::user()->id)
            ->update([
                'name'    => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]); 
      

        return redirect('/user-profile')->with('success','Data Profile Berhasil Diedit!');
    }

    public function tambahUser(Request $request)
    {
        try {
            $new = new User();
            $new->name = $request->name;
            $new->phone = $request->phone;
            $new->email = $request->email;
            $new->role = $request->role;
            $new->pw_text = $request->$request->password;
            $new->password = bcrypt($request->password);
    
            if ($new->save()) {
                return redirect()->back()->with('success','Data Admin Berhasil Dibuat!');
            }else{
                return redirect()->back()->with('failed','Data Admin Gagal Dibuat!');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('failed', $th->getMessage());
        }

    }
    public function updateUser(Request $request,$id)
    {
        try {
            $new = User::find($id);
            $new->name = $request->name;
            $new->phone = $request->phone;
            $new->email = $request->email;
            $new->role = $request->role;
            if ($request->password != null) {
                $new->pw_text = $request->password;
                $new->password = bcrypt($request->password);
            }
            if ($new->save()) {
                return redirect()->back()->with('success','Data Admin Berhasil Diedit!');
            }else{
                return redirect()->back()->with('failed','Data Admin Gagal Diedit!');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('failed', $th->getMessage());
        }


    }

    public function deleteUser($id){
        $user = User::find($id);
        
        if ($user->delete()) {
            return redirect()->back()->with('success','Data has been deleted');
        }else{
            return redirect()->back()->with('failed','Data Failed deleted');
        }
    }
}
