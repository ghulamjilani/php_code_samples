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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('dob');
            $table->string('gender');
            $table->string('address');
            $table->string('email');
            $table->string('marital_status');
            $table->string('nationality');
            $table->string('phone_no');
            $table->string('ni_number');
            $table->string('phone_no_p');
            $table->string('phone_no_s');
            $table->string('contract_start_date');
            $table->string('contract_end_date');
            $table->string('confirmation_date');
            $table->string('joining_date');
            $table->string('line_manager');
            $table->string('staff_manager');
            $table->string('job_location');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('emp_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
