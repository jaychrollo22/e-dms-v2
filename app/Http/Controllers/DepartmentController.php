<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Department;
use DB;

class DepartmentController extends Controller
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
        return view('pages.departments.index');
    }

    public function indexData(Request $request){

        $limit = $request->limit;

        $department = Department::orderBy('department','ASC');

        if(isset($request->search)){
            $department->where('department', 'LIKE', '%' . $request->search . '%');
        }

        return $department->paginate($limit);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'department' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $data = $request->all();
            $department = Department::where('id',$data['id'])->first();
            if($department){
                unset($data['id']);
                $department->update($data);
                DB::commit();
                $department = Department::where('id',$department->id)->first();
                return $status_data = [
                    'status'=>'success',
                    'department'=>$department,
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
