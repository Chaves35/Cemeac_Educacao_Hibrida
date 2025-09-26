<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            // ✅ Alinhado com o domínio funcional
            $table->enum('type', ['verdadeiro_falso', 'multipla_escolha', 'drag_drop', 'subjetiva']);
            $table->enum('difficulty_level', ['basico', 'intermediario', 'avancado'])->default('basico');
            $table->integer('estimated_duration')->nullable();
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('content_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['school_id', 'is_active']);
            $table->index(['type', 'difficulty_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
