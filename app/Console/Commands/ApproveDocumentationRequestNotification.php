<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;
use App\DocumentationRequest;
use App\Mail\ApproveDocumentationRequestEmail;
use Illuminate\Support\Facades\Mail;

class ApproveDocumentationRequestNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:approve_documentation_request_notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $request = $this->send();

        return $this->info($request);
    }

    public function send(){
        $requests = DocumentationRequest::with('document_upload_info','requestor_info','company_info','department_info')
                                            ->where(function($q){
                                                $q->where('email_notification_is_approve','')
                                                    ->orWhereNull('email_notification_is_approve');
                                            })
                                            ->where('status','Approved')
                                            ->get();
        $email_sent = 0;
        if($requests){

            
                                    
            foreach($requests as $request){

                $adco_users = User::select('id','email')->whereHas('roles',function($q){
                                            $q->where('role_id','9'); // ADCO
                                        })
                                        ->whereHas('company',function($q) use($request){
                                            $q->where('company_id',$request->company);
                                        })
                                        ->get();
                $adco_user_emails = [];
                if($adco_users){
                    foreach($adco_users as $key => $user){
                        $adco_user_emails[$key] = $user['email'];
                    }
                }
                
                $send_update = Mail::to($request->requestor_info->email)->cc($adco_user_emails)->send(new ApproveDocumentationRequestEmail($request));
                DocumentationRequest::where('id',$request->id)->update(['email_notification_is_approve'=>1]);
                $email_sent++;
            }
        }

        return $email_sent;
            
    }
}
