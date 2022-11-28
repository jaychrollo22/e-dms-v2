<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentationRequestDraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentation_request_drafts', function (Blueprint $table) {
            
            $table->id();
            $table->bigInteger('documentation_request_id')->nullable();
            $table->bigInteger('document_control_officer');
            
            $table->bigInteger('business_process_manager')->nullable();
            $table->bigInteger('business_process_manager_is_approve')->nullable();
            $table->bigInteger('requestor_immediate_head')->nullable();
            $table->bigInteger('requestor_immediate_head_is_approve')->nullable();
            $table->bigInteger('polcom')->nullable();
            $table->bigInteger('polcom_is_approve')->nullable();
            $table->bigInteger('polcom_chairman')->nullable();
            $table->bigInteger('polcom_chairman_is_approve')->nullable();

            $table->string('role_of_approval'); // Current Level - Business Process Manager, Immediate Head, Polcom, Polcom Chairman
            $table->string('role_of_approval_status'); //For Revision, For Approval

            $table->string('status')->nullable(); // For Approval , For Printing

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documentation_request_drafts');
    }
}
