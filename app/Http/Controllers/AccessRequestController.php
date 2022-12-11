<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\AccessRequest;
use App\User;

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

        $access_requests = AccessRequest::with('company_info','department_info')->orderBy('created_at','DESC');

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
                if($acces_request = AccessRequest::create($data)){
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
