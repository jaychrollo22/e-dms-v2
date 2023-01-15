<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;
use App\DocumentUpload;
use App\Mail\DocumentUploadProcessOwnerEmail;
use Illuminate\Support\Facades\Mail;

class DocumentUploadProcessOwnerNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:document_upload_process_owner_notification';

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

        $document_uploads = DocumentUpload::with(
                                            'company_info',
                                            'department_info',
                                            'document_category_info.tag_info',
                                            'process_owner_info',
                                            'users.user_info'
                                            )
                                            ->where(function($q){
                                                $q->whereNull('is_process_owner_notified',)
                                                    ->orWhere('is_process_owner_notified','');
                                            })
                                            ->where('status','Approved')
                                            ->get();
        
        $email_sent = 0;
        if($document_uploads){

            
                                    
            foreach($document_uploads as $document){
                if($document->process_owner_info){
                    // return json_encode($document,true);
                    $dco_users = User::select('id','email')->whereHas('roles',function($q){
                                                $q->where('role_id','3'); // ADCO
                                            })
                                            ->whereHas('company',function($q) use($document){
                                                $q->where('company_id',$document->company);
                                            })
                                            ->get();
                    $dco_user_emails = [];
                    if($dco_users){
                        foreach($dco_users as $key => $user){
                            $dco_user_emails[$key] = $user['email'];
                        }
                    }
                    
                    $send_update = Mail::to($document->process_owner_info->email)->cc($dco_user_emails)->send(new DocumentUploadProcessOwnerEmail($document));
                    DocumentUpload::where('id',$document->id)->update(['is_process_owner_notified'=>1]);
                    $email_sent++;
                }  
            }
        }

        return $email_sent;
    }
}
