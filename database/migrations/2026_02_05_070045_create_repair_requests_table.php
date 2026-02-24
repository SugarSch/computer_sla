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

         Schema::create('repair_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('label');
            $table->boolean('pauses_sla')->default(false);
            $table->boolean('is_final')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('repair_action_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();          // accept_job
            $table->string('label');                   // รับเรื่อง
            $table->foreignId('from_status')->nullable()->constrained('repair_statuses')->nullOnDelete(); // จาก repair_statuses
            $table->foreignId('to_status')->nullable()->constrained('repair_statuses')->nullOnDelete();   // จาก repair_statuses

            $table->json('allowed_roles');             // [role_id จาก roles] เป็น json
            $table->json('active_status');
            $table->boolean('affects_sla')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        Schema::create('sla_priorities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('response_time_minutes');
            $table->unsignedInteger('resolve_time_minutes');
            $table->timestamps();
        });

        Schema::create('repair_requests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');

            $table->foreignId('user_id')->constrained(); //ผู้แจ้ง

            $table->foreignId('repair_status_id')->constrained('repair_statuses');
            $table->foreignId('sla_priority_id')->constrained('sla_priorities');

            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users');
            $table->timestamps();
        });

        Schema::create('repair_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('repair_request_id')->constrained();
            $table->foreignId('user_id')->constrained('users');

            $table->foreignId('repair_action_type_id')->constrained('repair_action_types');
            $table->text('message')->nullable();

            $table->timestamps();
        });

        Schema::create('sla_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_request_id')->constrained();
            $table->timestamp('started_at');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('sla_pause_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sla_track_id')->constrained();
            $table->longText('reason')->nullable();
            $table->timestamp('paused_at');
            $table->timestamp('resumed_at')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_requests');
        Schema::dropIfExists('repair_statuses');
        Schema::dropIfExists('repair_logs');
        Schema::dropIfExists('repair_action_types');
    }
};
