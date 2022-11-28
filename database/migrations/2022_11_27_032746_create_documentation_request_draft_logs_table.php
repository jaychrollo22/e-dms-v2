<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentationRequestDraftLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentation_request_draft_logs', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('documentation_request_draft_id');
            $table->string('file_path');
            $table->string('status'); //For Approval, Revise, For Printing
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
        Schema::dropIfExists('documentation_request_draft_logs');
    }
}
