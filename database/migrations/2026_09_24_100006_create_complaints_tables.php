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
        Schema::create('complaint_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('complaint_number')->unique();
            $table->foreignId('complaint_category_id')->constrained('complaint_categories');
            $table->foreignId('reporter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reporter_name');
            $table->string('reporter_phone');
            $table->text('location_detail');
            $table->foreignId('village_id')->constrained('villages');
            $table->text('description');
            $table->timestampTz('reported_at')->nullable()->index();
            $table->foreignId('officer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->index();
            $table->text('verification_result')->nullable();
            $table->text('action_taken')->nullable();
            $table->foreignId('duplicate_of_id')->nullable()->constrained('complaints')->nullOnDelete();
            $table->timestampTz('resolved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['village_id', 'status']);
        });

        Schema::create('complaint_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->constrained('complaints')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('type')->default('photo'); // photo|document
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_attachments');
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('complaint_categories');
    }
};
