<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use App\UserLog;
use Auth;
use Carbon\Carbon;
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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticated(Request $request, $user) {
        $data = [
            'user_id'=>$user->id,
            'log_date'=>Carbon::now()->toDateTimeString()
        ];

        UserLog::create($data);

        $user_profile = User::with('immediate_head.user_info','department.department_info','company.company_info','roles')
                            ->where('id',$user->id)->first();
        
        $role_ids = [];
        $roles = [];
        if($user_profile->roles){
            $roles = json_decode($user_profile->roles->roles,true);
            foreach($roles as $k => $item){
                $role_ids[] = $item['id'];
            }
        }else{
            $role_ids = [2];
        }
        session([
            'user_profile'=>$user_profile,
            'roles'=>$roles,
            'role_ids'=>$role_ids,
        ]);

    }
}
