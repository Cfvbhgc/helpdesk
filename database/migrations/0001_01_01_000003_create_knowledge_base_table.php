<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_base', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('content');
            $table->string('category');
            $table->string('slug')->unique();
            $table->boolean('published')->default(false);
            $table->timestamps();

            $table->index('category');
            $table->index('published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_base');
    }
};
