<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DocumentCopyRequest;
use App\User;

use DB;
use Storage;
use Auth;


class DocumentCopyRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.document_copy_requests.index');
    }

    public function indexData(Request $request){

        $limit = $request->limit;

        $document_copy_requests = DocumentCopyRequest::with('document_upload_info','requestor_info','company_info','department_info')->orderBy('created_at','DESC');

        if(isset($request->search) && $request->search){
            $document_copy_requests->whereHas('requestor_info',function($q) use($request){
                $q->where('name', 'LIKE', '%' . $request->search . '%');
            });
        }
        if(isset($request->company)){
            $document_copy_requests->where('company', $request->company);
        }
        if(isset($request->department)){
            $document_copy_requests->where('department', $request->department);
        }

        return $document_copy_requests->paginate($limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.document_copy_requests.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::with('company','department')->where('id',Auth::user()->id)->first();
        $validate_request = [
            'document_upload_id' => 'required',
            'remarks' => 'required',
            'file_copy_type' => 'required'
        ];

        $this->validate($request, $validate_request);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['requested_date'] = date('Y-m-d');
            $data['requestor'] = $user->id;
            $data['company'] = $user->company->company_id;
            $data['department'] = $user->department->department_id;
            $data['status'] = 'New';
            if($document_upload = DocumentCopyRequest::create($data)){
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
    public function update(Request $request)
    {
        if($request->status == 'Approved'){
            $validate_request = [
                'expiration_date' => 'required',
            ];   
        }else{
            $validate_request = [
                'status_remarks' => 'required',
            ];
        }
        $this->validate($request, $validate_request);

        DB::beginTransaction();
        try {
            $document_copy_request = DocumentCopyRequest::where('id',$request->id)->first();
            if($document_copy_request){
                $data = $request->all();
                $document_copy_request->update($data);
                DB::commit();
                $document_copy_request = DocumentCopyRequest::with('document_upload_info','requestor_info','company_info','department_info')->where('id',$document_copy_request->id)->first();
                return $response = [
                    'status'=>'success',
                    'document_copy_request'=>$document_copy_request,
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
    public function destroy($id)
    {
        //
    }
}
