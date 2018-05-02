<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Session;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    //邮箱验证
    public function verify($id,$token)
    {
        // 获取 token
        $user = User::where('id',$id)->first();
        // 验证 token
        if($user->confirmation_token !== $token){
            // 清除 Session
            Session::flush();
            Session::regenerate();
            return redirect('/register/error');
        }
        // 更新数据
        $user->is_active = 1;
        $user->confirmation_token = str_random(40);
        $user->save();
        flash('邮箱验证成功','success');
        Auth::login($user);
        return redirect('/');
    }

    /*
     * 注册成功后跳转页面
     */
    public function success(){
        return view('mail.success');
    }

    /*
     * 激活失败后跳转页面
     */
    public function error(){
        return view('mail.error');
    }
}
