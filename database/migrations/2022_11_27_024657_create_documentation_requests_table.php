<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentationRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentation_requests', function (Blueprint $table) {
            $table->id();
            $table->bigint('department');
            $table->string('dicr_number');
            $table->bigint('requestor');
            $table->date('requested_date');
            $table->string('title');
            $table->date('proposed_effective_date');
            $table->string('type_of_request');
            $table->string('type_of_documented_information');
            $table->string('description_purpose_of_documentation');
            $table->string('status'); // New, For Approval , 
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
        Schema::dropIfExists('documentation_requests');
    }
}
