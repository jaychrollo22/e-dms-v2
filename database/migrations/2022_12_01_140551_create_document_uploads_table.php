<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_uploads', function (Blueprint $table) {

            $table->id();
            $table->string('control_code'); // For Confirmation if Auto Generated
            $table->string('title');
            $table->date('effective_date');
            $table->string('attachment_raw_file');
            $table->string('attachment_fillable_copy'); // If Forms Only
            $table->string('attachment_signed_copy'); // If Forms Only
            $table->bigInteger('document_category');
            $table->bigInteger('company');
            $table->bigInteger('department');
            $table->bigInteger('assign_stamp'); // To be confirmed if all documents need stamp

            $table->bigInteger('process_owner'); // To be confirmed if one process owner only
            $table->bigInteger('is_discussed');

            $table->bigInteger('notify_after_50_day');
            $table->bigInteger('notify_after_1_year_30_days_prior');
            $table->bigInteger('is_reviewed');
            $table->bigInteger('notify_after_1_year_not_review_15_days_after');

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
        Schema::dropIfExists('document_uploads');
    }
}
