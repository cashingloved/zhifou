<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function login(Request $request)
    {
        // VDL 验证请求的数据
        $this->validateLogin($request);

        // HTMLA 查询数据库用户登陆次数错误(1分钟内失败5次)
        if ($this->hasTooManyLoginAttempts($request)) {
            // FLE 触发事件监听
            $this->fireLockoutEvent($request);
            //SLR 这方法被调用意味着用户已经超过登录上限，此时方法会 back 到登录页，并携带'登录超过上限，请于58秒后再次登录'这样的提示；
            return $this->sendLockoutResponse($request);
        }

        // 登陆成功
        if ($this->attemptLogin($request)) {
            // Session 闪存信息
            flash('欢迎回来','success');
            // SLR(1) 重新生成 Session ID 清除错误次数 转到'/home'
            return $this->sendLoginResponse($request);
        }

        // 登陆失败
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    protected function attemptLogin(Request $request)
    {
        // $credentials = [ email => 'xxx' , password => 'xxx' , is_active => 1 ]
        $credentials = array_merge($this->credentials($request),['is_active'=>1]);
        // Illuminate\Auth\SessionGuard.php
        return $this->guard()->attempt(
            $credentials, $request->has('remember')
        );
    }

}
