<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\DocumentCopyRequest;

use App\Mail\DocumentCopyRequestLinkEmail;
use Illuminate\Support\Facades\Mail;

class DocumentCopyRequestLinkNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:document_copy_request_link';

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

        $document_copy_requests = DocumentCopyRequest::with('requestor_info')
                                                        ->where('status','Approved')
                                                        ->where(function($q){
                                                            $q->whereNull('share_link_notification',)
                                                                ->orWhere('share_link_notification','');
                                                        })
                                                        ->get();
        
        $email_sent = 0;
        if($document_copy_requests){          
            foreach($document_copy_requests as $request){
                if($request->requestor_info){
                    // return json_encode($document,true);
                    $dco_users = User::select('id','email')->whereHas('roles',function($q){
                                                $q->where('role_id','3'); // ADCO
                                            })
                                            ->whereHas('company',function($q) use($request){
                                                $q->where('company_id',$request->company);
                                            })
                                            ->get();
                    $dco_user_emails = [];
                    if($dco_users){
                        foreach($dco_users as $key => $user){
                            if($user['email']){
                                $dco_user_emails[$key] = $user['email'];
                            }
                            
                        }
                    }
                    
                    $send_update = Mail::to($request->requestor_info->email)->cc($dco_user_emails)->send(new DocumentCopyRequestLinkEmail($request));
                    DocumentCopyRequest::where('id',$request->id)->update(['share_link_notification'=>1]);
                    $email_sent++;
                }  
            }
        }

        return $email_sent;
    }
}
