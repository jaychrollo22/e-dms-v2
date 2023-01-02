<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DocumentationRequest;
use App\DocumentUpload;
use App\DocumentCopyRequest;
use App\AccessRequest;
use App\DocumentUploadUser;

use Auth;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       
        if(roleValidation()){
            return view('pages.home.dashboard');
        }else{
            return redirect('/home-user');
        }
        
    }

    public function userIndex(){
        return view('pages.home.user_dashboard');
    }

    public function dashboardData(){
        $new_document_request = DocumentationRequest::where('status','Pending')->count();
        $new_document_copy_request = DocumentCopyRequest::where('status','New')->count();
        $new_document_upload = DocumentUpload::where('status','Pending')->count();
        $new_access_request = AccessRequest::where('status','New')->count();

        return [
            'new_document_request'=>$new_document_request,
            'new_document_copy_request'=>$new_document_copy_request,
            'new_document_upload'=>$new_document_upload,
            'new_access_request'=>$new_access_request,
        ];
    }

    public function userDashboardData(){

        $total_document_request = DocumentationRequest::where('status','Pending')->where('requestor',Auth::user()->id)->count();
        $total_document_copy_request = DocumentCopyRequest::where('status','New')->where('requestor',Auth::user()->id)->count();
    
        $total_document_upload = DocumentUploadUser::select('id','user_id','status')->with('document_upload_info',)
                                                    ->where('status','1');

        $total_document_upload->where('user_id',Auth::user()->id);
        $total_document_upload->where('status','1');
        $total_document_upload->whereHas('document_upload_info',function($q){
            $q->where('is_discussed','1');
            $q->where('status','Approved');
            $q->whereDate('effective_date','<=',date('Y-m-d'));
        });
        
        $total_document_upload->whereHas('document_upload_info',function($q){
            $q->where('is_discontinuance','=',"")->orWhereNull('is_discontinuance');
        });

        $total_document_upload->whereHas('document_upload_info',function($q){
            $q->where('is_obsolete','=',"")->orWhereNull('is_obsolete');
        });

        $total_document_upload = $total_document_upload->get();

        return [
            'total_document_request'=>$total_document_request,
            'total_document_copy_request'=>$total_document_copy_request,
            'total_document_upload'=>count($total_document_upload),
        ];
    }
}
