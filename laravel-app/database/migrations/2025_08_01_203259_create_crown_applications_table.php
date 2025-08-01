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
        Schema::create('crown_applications', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('job_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('current_location');
            $table->string('profile_photo')->nullable();
            $table->string('cv_file')->nullable();
            $table->text('cover_letter')->nullable();
            $table->text('experience')->nullable();
            $table->string('previous_role')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
            
            $table->foreign('job_id')->references('id')->on('crown_jobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crown_applications');
    }
};
