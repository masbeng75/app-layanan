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
        Schema::create('service_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // DTSEN, PBI, REHSOS, etc.
            $table->string('name');
            $table->string('category');
            $table->text('description')->nullable();
            $table->string('handler')->default('generic'); // generic|dtsen|pbi
            $table->boolean('needs_assessment')->default(false);
            $table->integer('sla_days')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('service_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_type_id')->constrained('service_types')->cascadeOnDelete();
            $table->string('name');
            $table->boolean('is_mandatory')->default(true);
            $table->string('allowed_mimes')->default('pdf,jpg,png');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->foreignId('service_type_id')->constrained('service_types');
            $table->foreignId('submitter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('applicant_name');
            $table->char('applicant_nik', 16);
            $table->char('family_card_number', 16);
            $table->text('address');
            $table->foreignId('village_id')->constrained('villages');
            $table->string('phone');
            $table->timestampTz('submitted_at')->nullable()->index();
            $table->foreignId('officer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('work_unit_id')->nullable()->constrained('work_units')->nullOnDelete();
            $table->string('status')->index();
            $table->boolean('is_priority')->default(false);
            $table->text('verification_result')->nullable();
            $table->text('officer_notes')->nullable();
            $table->text('assessment_notes')->nullable();
            $table->text('service_result')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestampTz('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['service_type_id', 'status']);
            $table->index(['village_id', 'status']);
        });

        Schema::create('service_request_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained('service_requests')->cascadeOnDelete();
            $table->foreignId('service_requirement_id')->constrained('service_requirements')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('original_name');
            $table->string('verification_status')->default('pending'); // pending|valid|revision_needed
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_request_documents');
        Schema::dropIfExists('service_requests');
        Schema::dropIfExists('service_requirements');
        Schema::dropIfExists('service_types');
    }
};
