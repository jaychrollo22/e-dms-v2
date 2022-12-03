<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Company;
use DB;
class CompanyController extends Controller
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
        return view('pages.companies.index');
    }

    public function indexData(Request $request){

        $limit = $request->limit;

        $company = Company::orderBy('company_name','ASC');

        if(isset($request->search)){
            $company->where('company_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('company_code', 'LIKE', '%' . $request->search . '%');
        }

        return $company->paginate($limit);
    }

    public function companies(){
        return Company::select('id','company_name')->orderBy('company_name','ASC')->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'company_code' => 'required',
            'company_name' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $data = $request->all();
            if($company = Company::create($data)){
                DB::commit();
                return $status_data = [
                    'status'=>'success',
                    'company'=>$company,
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
    public function update(Request $request)
    {
        $this->validate($request, [
            'company_code' => 'required',
            'company_name' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $data = $request->all();
            $company = Company::where('id',$data['id'])->first();
            if($company){
                unset($data['id']);
                $company->update($data);
                DB::commit();
                $company = Company::where('id',$company->id)->first();
                return $status_data = [
                    'status'=>'success',
                    'company'=>$company,
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
