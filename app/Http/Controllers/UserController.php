<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\UserImmediateHead;
use App\UserDepartment;
use App\UserCompany;
use App\UserRole;
use Auth;
use DB;

class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.users.index');
    }

    public function indexData(Request $request){

        $limit = $request->limit;

        $user = User::with('immediate_head.user_info','department.department_info','company.company_info','roles')->orderBy('name','ASC');

        if(isset($request->search)){
            $user->where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('email', 'LIKE', '%' . $request->search . '%');
        }

        return $user->paginate($limit);
    }

    public function userOptions(Request $request){

        $department = isset($request->department) ? $request->department : "";
        $company = isset($request->company) ? $request->company : "";
        
        $user = User::select('id','name')
                    ->with('department.department_info','company.company_info')
                    ->where('status','Active')
                    ->when($department,function($q) use($department){
                        $q->whereHas('department',function($w) use($department){
                            $w->where('department_id',$department);
                        });
                    })
                    ->when($company,function($q) use($company){
                        $q->whereHas('company',function($w) use($company){
                            $w->where('company_id',$company);
                        });
                    })
                    ->orderBy('name','ASC')
                    ->get();
        
        if(isset($request->search_user)){
            if($request->search_user){
                $user = $user->where('name', 'LIKE', '%' . $request->search_user . '%');
            }
        }

        return $user;

    }
    public function immediateHeads(Request $request){

        $user = User::select('id','name')
                    ->where('status','Active')
                    ->orderBy('name','ASC')
                    ->get();
        return $user;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'immediate_head'=>  'required',
            'department'=>  'required',
            'company'=>  'required',
            'roles'=>  'required',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();

            unset($data['immediate_head']);
            unset($data['department']);
            unset($data['company']);
            unset($data['roles']);

            $data['password'] = Hash::make($data['password']);

            if($user = User::create($data)){

                if($request->immediate_head){
                    UserImmediateHead::create([
                        'user_id'=>$user->id,
                        'immediate_head' => $request->immediate_head
                    ]);
                }

                if($request->department){
                    UserDepartment::create([
                        'user_id'=>$user->id,
                        'department_id' => $request->department
                    ]);
                }

                if($request->company){
                    
                    UserCompany::create([
                        'user_id'=>$user->id,
                        'company_id' => $request->company
                    ]);  
                }
                
                if($request->roles){
                    UserRole::create([
                        'user_id'=>$user->id,
                        'roles' => $request->roles
                    ]);
                }

                DB::commit();
                return $status_data = [
                    'status'=>'success',
                    'user'=>$user,
                ];
            }
        }
        catch (Exception $e) {
            DB::rollBack();
            return 'error';
        } 



    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);
        DB::beginTransaction();
        try {
            $data = $request->all();

            unset($data['immediate_head']);
            unset($data['department']);
            unset($data['company']);
            unset($data['roles']);

            $user = User::where('id',$data['id'])
                            ->first();

            if($user){
                unset($data['id']);
                $user->update($data);

                if($request->immediate_head){
                    $check_immediate_head = UserImmediateHead::where('user_id',$request->id)
                                                ->first();

                    if($check_immediate_head){
                        $check_immediate_head->update([
                            'immediate_head' => $request->immediate_head
                        ]);
                    }else{
                        UserImmediateHead::create([
                            'user_id'=>$request->id,
                            'immediate_head' => $request->immediate_head
                        ]);
                    }
                }
                if($request->department){
                    $check_department = UserDepartment::where('user_id',$request->id)
                                                ->first();

                    if($check_department){
                        $check_department->update([
                            'department_id' => $request->department
                        ]);
                    }else{
                        UserDepartment::create([
                            'user_id'=>$request->id,
                            'department_id' => $request->department
                        ]);
                    }
                }
                if($request->company){
                    $company = UserCompany::where('user_id',$request->id)
                                                ->first();

                    if($company){
                        $company->update([
                            'company_id' => $request->company
                        ]);
                    }else{
                        UserCompany::create([
                            'user_id'=>$request->id,
                            'company_id' => $request->company
                        ]);
                    }
                }
                if($request->roles){
                    $roles = UserRole::where('user_id',$request->id)
                                                ->first();
                    

                    if($roles){
                        $roles->update([
                            'roles' => $request->roles
                        ]);
                    }else{
                        UserRole::create([
                            'user_id'=>$request->id,
                            'roles' => $request->roles
                        ]);
                    }
                }

                DB::commit();
                $user = User::with('immediate_head.user_info','department.department_info','company.company_info','roles')
                                ->where('id',$user->id)->first();
                return $status_data = [
                    'status'=>'success',
                    'user'=>$user,
                ];
            }else{
                return $data = [
                    'status'=>'error'
                ];
            }
        }
        catch (Exception $e) {
            DB::rollBack();
            return 'error';
        } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {

        $validator = $request->validate([
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ]);

        DB::beginTransaction();
        try {

            $user = User::where('id',$request->id)
                            ->first();

            if($user){
                $user->update([
                    'password'=>bcrypt($request->new_password)
                ]);

                DB::commit();

                $user = User::with('immediate_head.user_info','department.department_info','company.company_info','roles')
                                ->where('id',$user->id)->first();
                return $status_data = [
                    'status'=>'success',
                    'user'=>$user,
                ];
            }
        }
        catch (Exception $e) {
            DB::rollBack();
            return 'error';
        } 
    }

    public function userProfile(Request $request){
        $user_profile = User::with('immediate_head.user_info','department.department_info','company.company_info','roles')
                            ->where('id',Auth::user()->id)->first();
        return view('pages.users.user_profile',compact('user_profile'));
    }
}
