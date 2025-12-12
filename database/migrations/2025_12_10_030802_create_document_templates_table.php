<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();

            // proposal / rekomendasi
            $table->string('type'); // 'proposal' | 'rekomendasi'

            $table->string('title')->nullable(); // optional, misal "Template Proposal"
            $table->string('file_path');         // storage path
            $table->timestamps();

            $table->unique(['competition_id', 'type']); // 1 template per type per lomba
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_templates');
    }
};
