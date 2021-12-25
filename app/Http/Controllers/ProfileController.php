<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = auth()->user();
        $biometrics = DB::table('web_authn_credentials')->where('user_id',$user->id)->get();
        return view('profiles.profile',compact('user','biometrics'));
    }
    public function bimoetricData()
    {
        $user = auth()->user();
        $biometrics = DB::table('web_authn_credentials')->where('user_id',$user->id)->get();
        return view('components.biometric_data',compact('user','biometrics'))->render();
    
    }
    public function bimoetricDataDelete($id)
    {
        $user = auth()->user();
        $biometric = DB::table('web_authn_credentials')
        ->where('id',$id)
        ->where('user_id',$user->id)->delete();

        return 'success';
    }
}
