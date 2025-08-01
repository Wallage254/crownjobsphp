<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crown_jobs', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('title');
            $table->string('company');
            $table->string('category');
            $table->string('location');
            $table->text('description');
            $table->text('requirements');
            $table->integer('salary_min')->nullable();
            $table->integer('salary_max')->nullable();
            $table->string('job_type')->default('Full-time');
            $table->boolean('is_urgent')->default(false);
            $table->boolean('visa_sponsored')->default(true);
            $table->string('company_logo')->nullable();
            $table->json('workplace_images')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crown_jobs');
    }
};
