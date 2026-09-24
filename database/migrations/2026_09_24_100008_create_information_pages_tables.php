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
        Schema::create('information_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category'); // program|rehabilitation|disability|elderly|complaint|other
            $table->foreignId('service_type_id')->nullable()->constrained('service_types')->nullOnDelete();
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->text('procedure')->nullable();
            $table->string('service_hours')->nullable();
            $table->string('location')->nullable();
            $table->string('contact')->nullable();
            $table->string('publish_status')->default('draft'); // draft|published|archived
            $table->timestampTz('published_at')->nullable();
            $table->foreignId('manager_id')->constrained('users');
            $table->timestamps();
        });

        Schema::create('downloadable_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('information_page_id')->constrained('information_pages')->cascadeOnDelete();
            $table->string('name');
            $table->string('file_path');
            $table->string('version')->default('1.0');
            $table->boolean('is_current')->default(true);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('information_page_id')->nullable()->constrained('information_pages')->nullOnDelete();
            $table->text('question');
            $table->text('answer');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('page_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('information_page_id')->constrained('information_pages')->cascadeOnDelete();
            $table->date('visit_date');
            $table->integer('visit_count')->default(1);
            $table->timestamps();

            $table->unique(['information_page_id', 'visit_date']);
        });

        Schema::create('search_logs', function (Blueprint $table) {
            $table->id();
            $table->string('keyword');
            $table->integer('result_count')->default(0);
            $table->timestampTz('searched_at')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_logs');
        Schema::dropIfExists('page_visits');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('downloadable_forms');
        Schema::dropIfExists('information_pages');
    }
};
