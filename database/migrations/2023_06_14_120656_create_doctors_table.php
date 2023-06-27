<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->default(0);
            $table->string('name')->nullable(false);
            $table->longText('bio')->nullable();
            $table->string('avatarUrl')->nullable();
            $table->boolean('skill_mental')->default(false);
            $table->boolean('skill_beauty')->default(false);
            $table->boolean('skill_blood')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
