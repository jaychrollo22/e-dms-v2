<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DocumentUpload;
use App\DocumentUploadRevision;
use App\DocumentUploadUser;
use App\DocumentCopyRequest;
use App\User;
use Auth;
use DB;
use Storage;

use setasign\Fpdi\Fpdi;

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
    public function userIndex(){
        return view('pages.document_uploads.user_index');
    }

    public function indexData(Request $request){

        $limit = $request->limit;

        $document_uploads = DocumentUpload::with(
                                                'company_info',
                                                'department_info',
                                                'document_category_info.tag_info',
                                                'process_owner_info',
                                                'revisions',
                                                'users.user_info'
                                                )
                                                ->orderBy('created_at','DESC');

        if(isset($request->search)){
            $document_uploads->where('title', 'LIKE', '%' . $request->search . '%')
                                ->orWhere('control_code', 'LIKE', '%' . $request->search . '%');
        }

        if(isset($request->company)){
            $document_uploads->where('company',$request->company);
        }
        if(isset($request->department)){
            $document_uploads->where('department',$request->department);
        }

        return $document_uploads->paginate($limit);
    }
    public function userIndexData(Request $request){

        $limit = $request->limit;

        $document_uploads = DocumentUploadUser::with(
                                                'document_upload_info.company_info',
                                                'document_upload_info.department_info',
                                                'document_upload_info.document_category_info.tag_info',
                                                'document_upload_info.process_owner_info',
                                                'document_upload_info.revisions',
                                                'user_info'
                                                )
                                                ->where('status','1')
                                                ->orderBy('created_at','DESC');
    
        $document_uploads->where('user_id',Auth::user()->id);
        $document_uploads->where('status','1');
        $document_uploads->whereHas('document_upload_info',function($q){
                                        $q->where('is_discussed','1');
                                    });
        
        if(isset($request->search)){
            $document_uploads->whereHas('document_upload_info',function($q) use($request){
                return $q->where('title', 'LIKE', '%' . $request->search . '%')->orWhere('control_code', 'LIKE', '%' . $request->search . '%');
            });                 
        }

        return $document_uploads->paginate($limit);
    }

    public function documentUploadRequestCopyOptions(){
        $user = User::with('company')->where('id',Auth::user()->id)->first();

        $pending_document_copy_request = DocumentCopyRequest::select('document_upload_id')
                                                                ->where('requestor',$user->id)
                                                                ->where('is_expired',0)
                                                                ->get();
        $ids = [];
        if($pending_document_copy_request){
            foreach($pending_document_copy_request as $k => $item){
                $ids[$k] = $item->document_upload_id;
            }
        }

        return DocumentUpload::select('id','control_code','title')
                                ->where('company',$user->company->company_id)
                                ->whereNotIn('id',$ids)
                                ->orderBy('title','ASC')
                                ->get();

    }
    public function documentUploadRequestOptions(){
        $user = User::with('company')->where('id',Auth::user()->id)->first();
        return DocumentUpload::select('id','control_code','title')
                                ->where('company',$user->company->company_id)
                                ->where('status','Approved')
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

            $data['status'] = 'Pending'; // Validate if DCO Holdings, Default in pending

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
        
                    if($request->file('attachment_signed_copy_revision')){
                        $attachment_signed_copy_revision = $request->file('attachment_signed_copy_revision');   
                        $original_filename = $attachment_signed_copy_revision->getClientOriginalName();
                        $filename = 'SC-' . $revision_signed_copy_count . '-' . date('Ymd') . '_'. $original_filename;
                        $data['attachment_signed_copy_revision'] = $filename;
                        Storage::disk('public')->putFileAs('document_uploads', $attachment_signed_copy_revision , $filename);
                    }
                    
                    unset($data['id']);
                    unset($data['file_type']);
                    
                    $data['status'] = 'Pending'; // Validate if DCO Holdings, Default in pending

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

    public function updateApproval(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
            'status_remarks' => 'required',
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

    public function userAcknowledgeDocument(Request $request){
        DB::beginTransaction();
        try {
            $data = $request->all();
            $document_upload_user = DocumentUploadUser::with(
                                'document_upload_info.company_info',
                                'document_upload_info.department_info',
                                'document_upload_info.document_category_info.tag_info',
                                'document_upload_info.process_owner_info',
                                'document_upload_info.revisions',
                                'user_info'
                                )
                                ->where('id',$data['id'])
                                ->first();

            if($document_upload_user){
                unset($data['id']);
                $document_upload_user->update($data);
                DB::commit();
               
                return $status_data = [
                    'status'=>'success',
                    'document_upload_user'=>$document_upload_user,
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

    public function getUsers(Request $request){
        return $document_uploads = DocumentUploadUser::with('user_info.department.department_info','user_info.company.company_info')
                                            ->where('document_upload_id',$request->id)
                                            ->where('status','1')
                                            ->orderBy('created_at','DESC')
                                            ->get();
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

    public function removeUser(Request $request){

        DB::beginTransaction();
        try {
            
            $document_user_ids = json_decode($request->document_user_ids);
            $document_upload = DocumentUpload::where('id',$request->id)->first();

            if($document_user_ids && $document_upload){
                $count = 0;
                foreach($document_user_ids as $document_user_id){
                    
                    $document_upload_user = [
                        'status'=>'0',
                    ];

                    $check = DocumentUploadUser::where('id',$document_user_id)->first();
                    if($check){
                        $check->update($document_upload_user);
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

    public function allowPrintUser(Request $request){

        DB::beginTransaction();
        try {
            
            $document_user_ids = json_decode($request->document_user_ids);
            $document_upload = DocumentUpload::where('id',$request->id)->first();

            if($document_user_ids && $document_upload){
                $count = 0;
                foreach($document_user_ids as $document_user_id){
                    
                    $document_upload_user = [
                        'can_print'=>1,
                    ];

                    $check = DocumentUploadUser::where('id',$document_user_id)->first();
                    if($check){
                        $check->update($document_upload_user);
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

    public function allowDownloadUser(Request $request){

        DB::beginTransaction();
        try {
            
            $document_user_ids = json_decode($request->document_user_ids);
            $document_upload = DocumentUpload::where('id',$request->id)->first();

            if($document_user_ids && $document_upload){
                $count = 0;
                foreach($document_user_ids as $document_user_id){
                    
                    $document_upload_user = [
                        'can_download'=>1,
                    ];

                    $check = DocumentUploadUser::where('id',$document_user_id)->first();
                    if($check){
                        $check->update($document_upload_user);
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

    public function saveDocumentUploadUserPrint(Request $request){

        DB::beginTransaction();
        try {

            $document_upload_user = DocumentUploadUser::with('user_info.department.department_info','user_info.company.company_info')->where('id',$request->id)->first();

            if($document_upload_user){

                $data['can_print'] = $document_upload_user->can_print == '1' ? 0 : 1;

                $document_upload_user->update($data);
                DB::commit();

                return $response = [
                    'status'=>'saved',
                    'document_upload_user'=>$document_upload_user
                ];

            }

        }catch (Exception $e) {
            DB::rollBack();
            return $reponse = [
                'status'=>'error'
            ];
        }
    }

    public function saveDocumentUploadUserDownload(Request $request){

        DB::beginTransaction();
        try {

            $document_upload_user = DocumentUploadUser::with('user_info.department.department_info','user_info.company.company_info')->where('id',$request->id)->first();

            if($document_upload_user){

                $data['can_download'] = $document_upload_user->can_download == '1' ? 0 : 1;

                $document_upload_user->update($data);
                DB::commit();

                return $response = [
                    'status'=>'saved',
                    'document_upload_user'=>$document_upload_user
                ];

            }

        }catch (Exception $e) {
            DB::rollBack();
            return $reponse = [
                'status'=>'error'
            ];
        }
    }

    public function documentUploadSignedCopy(Request $request){
        
        $document_upload = DocumentUpload::with('company_info')->where('id',$request->id)->first();

        if($document_upload){

            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile("storage/document_uploads/".$document_upload->attachment_signed_copy);
            
            for($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {

                $pdf->AddPage('P','Legal');

                $tplIdx = $pdf->importPage(1);
                $pdf->useTemplate($tplIdx, 0, 0, 210);

                $pageWidth = $pdf->GetPageWidth();
                $pageHeight = $pdf->GetPageHeight();

                // Assign Stamp
                if($document_upload->assign_stamp == '1' && $document_upload->company_info->stamp){
                    $pdf->Image('storage/stamps/'.$document_upload->company_info->stamp, 10,$pageHeight - 23,50,0,'PNG');
                }

            }
            
            

            $pdf->Output('I', $document_upload->title . '.pdf');
            exit();
        }
    }

    public function downloadDocumentUploadSignedCopy(Request $request){
        
        $document_upload = DocumentUpload::with('company_info')->where('id',$request->id)->first();

        if($document_upload){

            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile("storage/document_uploads/".$document_upload->attachment_signed_copy);
            
            for($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {

                $pdf->AddPage('P','Legal');

                $tplIdx = $pdf->importPage(1);
                $pdf->useTemplate($tplIdx, 0, 0, 210);

                $pageWidth = $pdf->GetPageWidth();
                $pageHeight = $pdf->GetPageHeight();

                // Assign Stamp
                if($document_upload->assign_stamp == '1' && $document_upload->company_info->stamp){
                    $pdf->Image('storage/stamps/'.$document_upload->company_info->stamp, 10,$pageHeight - 23,50,0,'PNG');
                }

            }
            
            

            $pdf->Output('D', $document_upload->title . '.pdf');
            exit();
        }
    }
}
