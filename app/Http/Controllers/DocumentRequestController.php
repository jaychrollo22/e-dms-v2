<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DocumentationRequest;
use App\User;
use App\DocumentUpload;

use DB;
use Storage;
use Auth;

class DocumentRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role_ids = json_encode(session('role_ids'),true);
        return view('pages.document_requests.index',compact('role_ids'));
    }
    public function userIndex()
    {
        return view('pages.document_requests.user_index');
    }

    public function indexData(Request $request){

        $limit = $request->limit;

        $document_requests = DocumentationRequest::with('document_upload_info','requestor_info','company_info','department_info')->orderBy('created_at','DESC');

        if(isset($request->search)){
            $document_requests->where('title', 'LIKE', '%' . $request->search . '%')
                                ->orWhere('dicr_number', 'LIKE', '%' . $request->search . '%');
        }
        if(isset($request->company)){
            $document_requests->where('company',$request->company);
        }
        if(isset($request->department)){
            $document_requests->where('department',$request->department);
        }
        if(isset($request->type_of_request)){
            $document_requests->where('type_of_request',$request->type_of_request);
        }
        if(isset($request->status)){
            $document_requests->where('status',$request->status);
        }

        return $document_requests->paginate($limit);
    }
    
    public function userIndexData(Request $request){

        $limit = $request->limit;

        $document_requests = DocumentationRequest::with('document_upload_info','requestor_info','company_info','department_info')
                                                    ->where('requestor',Auth::user()->id)
                                                    ->orderBy('created_at','DESC');

        if(isset($request->search)){
            $document_requests->where(function($q) use($request){
                $q->where('title', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('dicr_number', 'LIKE', '%' . $request->search . '%');
            });
                                
        }

        return $document_requests->paginate($limit);
    }

    public function generateFilterRequests(Request $request){

        if($request->company_ids){
            $company_ids = json_decode($request->company_ids);
            $document_requests = DocumentationRequest::with('document_upload_info','requestor_info','company_info','department_info')
                                                    ->whereIn('company',$company_ids)
                                                    ->orderBy('created_at','DESC')
                                                    ->get();
        
            return $document_requests;
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.document_requests.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user = User::with('department.department_info','company.company_info')->where('id',Auth::user()->id)->first();

        if($request->type_of_request == 'Discontinuance' || $request->type_of_request == 'Obsolete'){
            $validate_request = [
                'title' => 'required',
                'proposed_effective_date' => 'required',
                'type_of_request' => 'required',
                'type_of_documented_information' => 'required',
                'description_purpose_of_documentation' => 'required',
            ];
        }else{
            $validate_request = [
                'title' => 'required',
                'proposed_effective_date' => 'required',
                'type_of_request' => 'required',
                'type_of_documented_information' => 'required',
                'description_purpose_of_documentation' => 'required',
                'attachment_file' => 'required|mimes:png,jpg,jpeg,csv,txt,xlx,xls,zip,pdf,docx'
            ];
        }
        

        $this->validate($request, $validate_request);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['company'] =$user->company->company_info->id;
            $data['department'] =$user->department->department_info->id;
            $data['requestor'] = Auth::user()->id;
            $data['requested_date'] = date('Y-m-d');
            $data['status'] = 'Pending';

            if($request->file('attachment_file')){
                $attachment_raw_file = $request->file('attachment_file');   
                $original_filename = $attachment_raw_file->getClientOriginalName();
                $filename = date('Ymd') . '_'. $original_filename;
                $data['attachment_file'] = $filename;
                Storage::disk('public')->putFileAs('document_requests', $attachment_raw_file , $filename);
            }

            if($request->type_of_request == 'Discontinuance'){
                $data['immediate_head_approval'] = 'For Approval';
            }
            
            if($request->type_of_request == 'Obsolete'){
                $data['immediate_head_approval'] = 'For Approval';
            }

            if($document_request = DocumentationRequest::create($data)){
                DB::commit();
                $dicr_number = $user->company->company_info->company_code . '-' . date('Y'). '-'.str_pad($document_request->id, 3, '0', STR_PAD_LEFT);//DICR Number
                DocumentationRequest::where('id',$document_request->id)->update([
                    'dicr_number'=> $dicr_number
                ]);
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
        if($request->type_of_request == 'Discontinuance' || $request->type_of_request == 'Obsolete'){
            $validate_request = [
                'title' => 'required',
                'proposed_effective_date' => 'required',
                'type_of_request' => 'required',
                'type_of_documented_information' => 'required',
                'description_purpose_of_documentation' => 'required',
            ];
        }else{
            $validate_request = [
                'title' => 'required',
                'proposed_effective_date' => 'required',
                'type_of_request' => 'required',
                'type_of_documented_information' => 'required',
                'description_purpose_of_documentation' => 'required',
            ];
        }
        

        $this->validate($request, $validate_request);


        DB::beginTransaction();
        try {
            $document_request = DocumentationRequest::where('id',$request->id)->first();
            if($document_request){
                $data = $request->all();

                if(isset($request->attachment_file)){
                    if($request->file('attachment_file')){
                        $attachment_raw_file = $request->file('attachment_file');   
                        $original_filename = $attachment_raw_file->getClientOriginalName();
                        $filename = date('Ymd') . '_'. $original_filename;
                        $data['attachment_file'] = $filename;
                        Storage::disk('public')->putFileAs('document_requests', $attachment_raw_file , $filename);
                    }
                }

                $document_request->update($data);
                DB::commit();
                $document_request = DocumentationRequest::with('document_upload_info','requestor_info','company_info','department_info')->where('id',$document_request->id)->first();
                return $response = [
                    'status'=>'success',
                    'document_request'=>$document_request,
                ];
            }
        }
        catch (Exception $e) {
            DB::rollBack();
            return 'error';
        } 

    }

    public function updateApproval(Request $request)
    {
        $validate_request = [
            'status_remarks' => 'required',
        ];

        $this->validate($request, $validate_request);

        DB::beginTransaction();
        try {
            $document_request = DocumentationRequest::where('id',$request->id)->first();
            if($document_request){
                $data = $request->all();

                if($document_request->type_of_request == 'Discontinuance'){
                    if($request->status == 'Approved'){
                        DocumentUpload::where('id',$document_request->document_upload_id)
                                    ->update([
                                        'is_discontinuance'=>1
                                    ]);
                    }
                }
                if($document_request->type_of_request == 'Obsolete'){
                    if($request->status == 'Approved'){
                        DocumentUpload::where('id',$document_request->document_upload_id)
                                    ->update([
                                        'is_obsolete'=>1
                                    ]);
                    }
                }

                $document_request->update($data);
                DB::commit();
                $document_request = DocumentationRequest::with('document_upload_info','requestor_info','company_info','department_info')->where('id',$document_request->id)->first();
                return $response = [
                    'status'=>'success',
                    'document_request'=>$document_request,
                ];
            }
        }
        catch (Exception $e) {
            DB::rollBack();
            return 'error';
        } 

    }


    public function updateDiscontinuanceImmediateHeadApproval(Request $request){
        DB::beginTransaction();
        try {
            $document_request = DocumentationRequest::where('id',$request->id)->first();
            if($document_request){
                $data = $request->all();
                $document_request->update($data);
                DB::commit();
                return $response = [
                    'status'=>'success',
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
