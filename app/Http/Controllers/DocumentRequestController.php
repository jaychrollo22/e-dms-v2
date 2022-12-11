<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DocumentationRequest;
use App\User;

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
        return view('pages.document_requests.index');
    }

    public function indexData(Request $request){

        $limit = $request->limit;

        $document_requests = DocumentationRequest::with('requestor_info','company_info','department_info')->orderBy('created_at','DESC');

        if(isset($request->search)){
            $document_requests->where('title', 'LIKE', '%' . $request->search . '%');
        }

        return $document_requests->paginate($limit);
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

        $validate_request = [
            'title' => 'required',
            'proposed_effective_date' => 'required',
            'type_of_request' => 'required',
            'type_of_documented_information' => 'required',
            'description_purpose_of_documentation' => 'required',
            'attachment_file' => 'required|mimes:png,jpg,jpeg,csv,txt,xlx,xls,zip,pdf,docx'
        ];

        $this->validate($request, $validate_request);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['company'] =$user->company->company_info->id;
            $data['department'] =$user->department->department_info->id;
            $data['requestor'] = Auth::user()->id;
            $data['requested_date'] = date('Y-m-d');

            if($request->file('attachment_file')){
                $attachment_raw_file = $request->file('attachment_file');   
                $original_filename = $attachment_raw_file->getClientOriginalName();
                $filename = date('Ymd') . '_'. $original_filename;
                $data['attachment_file'] = $filename;
                Storage::disk('public')->putFileAs('document_requests', $attachment_raw_file , $filename);
            }

            if($document_request = DocumentationRequest::create($data)){
                DB::commit();
                DocumentationRequest::where('id',$document_request->id)->update([
                    'dicr_number'=> date('Ymd') . '-' . str_pad($document_request->id, 4, '0', STR_PAD_LEFT) 
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
