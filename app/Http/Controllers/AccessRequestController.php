<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\AccessRequest;
use App\User;
use App\UserDepartment;
use App\UserCompany;
use App\UserImmediateHead;
use App\UserRole;

use Auth;
use DB;

class AccessRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.access_requests.index');
    }

    public function indexData(Request $request){

        $limit = $request->limit;

        $access_requests = AccessRequest::with('immediate_head_info','company_info','department_info')->orderBy('created_at','DESC');

        if(isset($request->search)){
            $access_requests->where('name', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('email', 'LIKE', '%' . $request->search . '%');
        }

        return $access_requests->paginate($limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.access_requests.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::where('email',$request->email)->first();

        if(empty($user)){
            $validate_request = [
                'email' => 'required|unique:access_requests,email',
                'name' => 'required',
                'department' => 'required',
                'company' => 'required'
            ];

            $this->validate($request, $validate_request);

            DB::beginTransaction();
            try {
                $data = $request->all();
                $data['requested_date'] = date('Y-m-d');
                $data['status'] = 'New';
                if(AccessRequest::create($data)){
                    DB::commit();
                    return $status_data = [
                        'status'=>'success'
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
        }else{
            return $data = [
                'status'=>'error',
                'message'=>'User already exist.'
            ];
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
        if($request->status == 'Approved'){
            $validate_request = [
                'initial_password' => 'required',
                'status_remarks' => 'required',
            ];
        }else{
            $validate_request = [
                'status_remarks' => 'required'
            ];
        }
        
        $this->validate($request, $validate_request);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $access_request = AccessRequest::with('immediate_head_info','company_info','department_info')->where('id',$request->id)->first();
            if($access_request){
                unset($data['id']);
                if($access_request->update($data)){
                        if($request->status == 'Approved'){
                            $user_data = [
                                'name'=>$access_request->name,
                                'email'=>$access_request->email,
                                'password'=>$access_request->initial_password,
                                'status'=>'Active',
                            ];
                            $user = User::where('email',$access_request->email)->first();
                            if(empty($user)){
                                $user_data['password'] = Hash::make($access_request->initial_password);
                                if($user = User::create($user_data)){
                                    if($request->immediate_head){
                                        UserImmediateHead::create([
                                            'user_id'=>$user->id,
                                            'immediate_head' => $request->immediate_head
                                        ]);
                                    }

                                    if($access_request->department){
                                        UserDepartment::create([
                                            'user_id'=>$user->id,
                                            'department_id' => $access_request->department
                                        ]);
                                    }
                                    if($access_request->company){
                                        UserCompany::create([
                                            'user_id'=>$user->id,
                                            'company_id' => $access_request->company
                                        ]);  
                                    }

                                    if($request->roles){
                                        $roles = json_decode($request->roles, true);
                                        foreach($roles as $role){
                                            if($role['id']){
                                                $has_role = UserRole::where('user_id',$user->id)->where('role_id',$role['id'])->first();
                                                if(empty($has_role)){
                                                    UserRole::create([
                                                        'user_id'=>$user->id,
                                                        'role_id' => $role['id'],
                                                        'roles' => json_encode($role,true)
                                                    ]);
                                                }
                                            }
                                        }
                                    }
    
                                    DB::commit();
                                }
                            }
                            
                        }
                        
                        return $status_data = [
                            'status'=>'success',
                            'access_request'=>$access_request
                        ];
                }else{
                    return $data = [
                        'status'=>'error'
                    ];
                }
            }
        }
        catch (Exception $e) {
            DB::rollBack();
            return 'error';
        }
    }

}
