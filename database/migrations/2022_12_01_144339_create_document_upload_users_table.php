<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentUploadUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_upload_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('document_upload_id');
            $table->bigInteger('user_id');
            $table->bigInteger('can_view');
            $table->bigInteger('can_download');
            $table->bigInteger('can_print');
            $table->bigInteger('can_fill');
            $table->string('attachment_fill');
            $table->string('is_acknowledge');
            $table->string('status'); // Active / Inactive
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
        Schema::dropIfExists('document_upload_users');
    }
}
