<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentCopyRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_copy_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company');
            $table->bigInteger('department');
            $table->string('remarks');
            $table->bigInteger('requested_by');
            $table->string('file_copy_type');
            $table->string('status');
            $table->string('dco');
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
        Schema::dropIfExists('document_copy_requests');
    }
}
