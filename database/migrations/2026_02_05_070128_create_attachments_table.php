<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_request_id')->constrained(); //id ของ request ที่แจ้งซ่อม
            $table->foreignId('uploaded_by')->constrained('users'); //id ของ user ที่อัพโหลด
            $table->string('file_name')->nullable(); //เก็บที่ /storage/app/public/request_file/
            $table->string('file_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
