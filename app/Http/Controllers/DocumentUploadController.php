<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DocumentUpload;
use App\DocumentUploadRevision;
use App\DocumentUploadUser;
use App\User;
use Auth;
use DB;
use Storage;

class DocumentUploadController extends Controller
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
        return view('pages.document_uploads.index');
    }

    public function indexData(Request $request){

        $limit = $request->limit;

        $document_uploads = DocumentUpload::with('company_info','department_info','document_category_info.tag_info','process_owner_info','revisions','users')->orderBy('created_at','DESC');

        if(isset($request->search)){
            $document_uploads->where('title', 'LIKE', '%' . $request->search . '%');
        }

        return $document_uploads->paginate($limit);
    }

    public function documentUploadRequestCopyOptions(){
        $user = User::with('company')->where('id',Auth::user()->id)->first();
        return DocumentUpload::select('id','control_code','title')
                                ->where('company',$user->company->company_id)
                                ->orderBy('title','ASC')
                                ->get();

    }

    public function store(Request $request)
    {
        $validate_request = [
            'title' => 'required',
            'effective_date' => 'required',
            'document_category' => 'required',
            'company' => 'required',
            'department' => 'required',
        ];

        $this->validate($request, $validate_request);

        DB::beginTransaction();
        try {
            $data = $request->all();
            
            if($request->file('attachment_raw_file')){
                $attachment_raw_file = $request->file('attachment_raw_file');   
                $original_filename = $attachment_raw_file->getClientOriginalName();
                $filename = 'RF-'.date('Ymd') . '_'. $original_filename;
                $data['attachment_raw_file'] = $filename;
                Storage::disk('public')->putFileAs('document_uploads', $attachment_raw_file , $filename);
            }
            
            if($request->file('attachment_fillable_copy')){
                $attachment_fillable_copy = $request->file('attachment_fillable_copy');   
                $original_filename = $attachment_fillable_copy->getClientOriginalName();
                $filename = 'FC-'. date('Ymd') . '_'. $original_filename;
                $data['attachment_fillable_copy'] = $filename;
                Storage::disk('public')->putFileAs('document_uploads', $attachment_fillable_copy , $filename);
            }

            if($request->file('attachment_signed_copy')){
                $attachment_signed_copy = $request->file('attachment_signed_copy');   
                $original_filename = $attachment_signed_copy->getClientOriginalName();
                $filename = 'SC-' . date('Ymd') . '_'. $original_filename;
                $data['attachment_signed_copy'] = $filename;
                Storage::disk('public')->putFileAs('document_uploads', $attachment_signed_copy , $filename);
            }


            if($document_upload = DocumentUpload::create($data)){
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

    public function storeRevision(Request $request){

        DB::beginTransaction();
        try {

            $document_upload = DocumentUpload::where('id',$request->id)->first();

            if($document_upload){

                $data = $request->all();
              
                $old_attachment = '';

                if($request->file_type == 'Raw File'){
                    $old_attachment = $document_upload->attachment_raw_file;
                    $revision_raw_file_count = DocumentUploadRevision::where('document_upload_id',$document_upload->id)
                                                                        ->where('file_type','Raw File')
                                                                        ->count();
                }
                else if($request->file_type == 'Fillable Copy'){
                    $old_attachment = $document_upload->attachment_fillable_copy;
                    $revision_fillable_copy_count = DocumentUploadRevision::where('document_upload_id',$document_upload->id)
                                                                        ->where('file_type','Fillable Copy')
                                                                        ->count();
                }
                else if($request->file_type == 'Signed Copy'){
                    $old_attachment = $document_upload->attachment_signed_copy;
                    $revision_signed_copy_count = DocumentUploadRevision::where('document_upload_id',$document_upload->id)
                                                                        ->where('file_type','Signed Copy')
                                                                        ->count();
                }

                $revision = [
                    'document_upload_id'=>$document_upload->id,
                    'attachment'=>$old_attachment,
                    'file_type'=>$request->file_type,
                ];

                if($save = DocumentUploadRevision::create($revision)){

                    if($request->file('attachment_raw_file')){
                        $attachment_raw_file = $request->file('attachment_raw_file');   
                        $original_filename = $attachment_raw_file->getClientOriginalName();
                        $filename = 'RF-'. $revision_raw_file_count . '-' .date('Ymd') . '_'. $original_filename;
                        $data['attachment_raw_file'] = $filename;
                        Storage::disk('public')->putFileAs('document_uploads', $attachment_raw_file , $filename);
                    }
                    
                    if($request->file('attachment_fillable_copy')){
                        $attachment_fillable_copy = $request->file('attachment_fillable_copy');   
                        $original_filename = $attachment_fillable_copy->getClientOriginalName();
                        $filename = 'FC-'. $revision_fillable_copy_count . '-' . date('Ymd') . '_'. $original_filename;
                        $data['attachment_fillable_copy'] = $filename;
                        Storage::disk('public')->putFileAs('document_uploads', $attachment_fillable_copy , $filename);
                    }
        
                    if($request->file('attachment_signed_copy')){
                        $attachment_signed_copy = $request->file('attachment_signed_copy');   
                        $original_filename = $attachment_signed_copy->getClientOriginalName();
                        $filename = 'SC-' . $revision_signed_copy_count . '-' . date('Ymd') . '_'. $original_filename;
                        $data['attachment_signed_copy'] = $filename;
                        Storage::disk('public')->putFileAs('document_uploads', $attachment_signed_copy , $filename);
                    }
                    
                    unset($data['id']);
                    unset($data['file_type']);

                    $document_upload->update($data);

                    DB::commit();

                    $document_upload = DocumentUpload::with('company_info','department_info','document_category_info.tag_info','process_owner_info','revisions','users')->where('id',$document_upload->id)->first();
                    
                    return $response = [
                        'status'=>'success',
                        'document_upload'=>$document_upload,
                    ];

                }

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
            'title' => 'required',
            'effective_date' => 'required',
            'document_category' => 'required',
            'company' => 'required',
            'department' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $data = $request->all();
            $document_upload = DocumentUpload::where('id',$data['id'])->first();
            if($document_upload){
                unset($data['id']);
                $document_upload->update($data);
                DB::commit();
                $document_upload = DocumentUpload::with('company_info','department_info','document_category_info.tag_info','process_owner_info','revisions','users')->where('id',$document_upload->id)->first();
                return $status_data = [
                    'status'=>'success',
                    'document_upload'=>$document_upload,
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

    public function storeUser(Request $request){

        DB::beginTransaction();
        try {
            
            $user_ids = json_decode($request->user_ids);
            $document_upload = DocumentUpload::where('id',$request->id)->first();

            if($user_ids && $document_upload){
                $count = 0;
                foreach($user_ids as $user_id){
                    $document_upload_user = [
                        'document_upload_id'=>$request->id,
                        'user_id'=>$user_id,
                        'status'=>'1',
                    ];
                    $check = DocumentUploadUser::where('document_upload_id',$request->id)
                                                ->where('user_id',$user_id)
                                                ->first();
                    if($check){
                        $check->update($document_upload_user);
                        $count++;
                    }else{
                        DocumentUploadUser::create($document_upload_user);
                        $count++;
                    }
                }

                DB::commit();

                $document_upload = DocumentUpload::with('company_info','department_info','document_category_info.tag_info','process_owner_info','revisions','users')->where('id',$document_upload->id)->first();
                    
                return $response = [
                    'status'=>'success',
                    'count'=> $count,
                    'document_upload'=>$document_upload,
                ];
            }

        }
        catch (Exception $e) {
            DB::rollBack();
            return 'error';
        } 
    }
}
