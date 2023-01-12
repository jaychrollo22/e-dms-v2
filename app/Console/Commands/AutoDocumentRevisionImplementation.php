<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\DocumentUpload;
class AutoDocumentRevisionImplementation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:auto_document_revision_implementation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Revision Implementation';

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
        $revisions = $this->revisions();

        return $this->info($revisions);
    }

    public function revisions(){

        $documents = DocumentUpload::whereDate('implementation_date','=',date('Y-m-d'))->get();

        if($documents){
            $count = 0;
            foreach($documents as $document){
                if($document->attachment_signed_copy_revision){
                    //Update from Revision
                    DocumentUpload::where('id',$document->id)
                                ->update([
                                    'attachment_signed_copy'=>$document->attachment_signed_copy_revision,
                                    'attachment_signed_copy_revision'=>null,
                                    'implementation_date'=>null,
                                    'status'=>'Pending',
                                ]);
                    $count++;
                }
            }

            return $count;
        }
    }
}
