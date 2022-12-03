<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use DB;

class RoleController extends Controller
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

    public function index(){
        return view('pages.roles.index');
    }

    public function indexData(Request $request){

        $limit = $request->limit;

        $role = Role::orderBy('name','ASC');

        if(isset($request->search)){
            $role->where('name', 'LIKE', '%' . $request->search . '%');
        }

        return $role->paginate($limit);
    }

    public function roles(){
        return Role::select('id','name')->orderBy('name','ASC')->get();
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $data = $request->all();
            $role = Role::where('id',$data['id'])->first();
            if($role){
                unset($data['id']);
                $role->update($data);
                DB::commit();
                $role = Role::where('id',$role->id)->first();
                return $status_data = [
                    'status'=>'success',
                    'role'=>$role,
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
}
