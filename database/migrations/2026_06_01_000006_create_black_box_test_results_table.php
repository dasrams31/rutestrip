<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('black_box_test_results', function (Blueprint $table) {
            $table->id();
            $table->string('test_case_id')->unique();
            $table->string('feature');
            $table->text('scenario');
            $table->text('expected_result');
            $table->text('actual_result');
            $table->string('status')->default('pass');
            $table->text('notes')->nullable();
            $table->timestamp('tested_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('black_box_test_results');
    }
};
