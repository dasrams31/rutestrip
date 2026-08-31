<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommendation_evaluation_queries', function (Blueprint $table) {
            $table->id();
            $table->string('query');
            $table->json('query_embedding')->nullable();
            $table->json('relevant_route_ids');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendation_evaluation_queries');
    }
};
