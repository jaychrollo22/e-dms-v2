<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Department;
use DB;

class DepartmentController extends Controller
{
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

    public function departments(){
        return Department::select('id','department')->orderBy('department','ASC')->get();
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'department' => 'required',
            'status' => 'required',
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'department' => 'required',
            'status' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $data = $request->all();
            if($department = Department::create($data)){
                DB::commit();
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
