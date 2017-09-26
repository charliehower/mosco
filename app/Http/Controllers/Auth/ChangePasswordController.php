<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function change(Request $request)
    {
        $password = $request->input('password');
        $repassword = $request->input('repassword');

        if ($password != $repassword)
        return "<script language='javascript' type='text/javascript'>".
                "alert('两次密码不一致！');".
                "window.location.href=\"/\";".
            "</script>  ";
        $user = User::where('id',Auth::user()->id)->first();
        $input = array();
        $input['password'] = Hash::make($password);
        $a = $user->update($input);
        return "<script language='javascript' type='text/javascript'>".
                "alert('修改成功');".
                "window.location.href=\"/\";".
            "</script>  ";
    }

    public function index(){
        return view('auth.changepassword');
    }

}
