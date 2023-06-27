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
        Schema::create('therapies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->longText('description')->nullable();
            $table->string('category')->default('');
            $table->integer('minDuration')->unsigned()->default(0);
            $table->integer('maxDuration')->unsigned()->default(30);
            $table->foreignIdFor(\App\Models\Doctor::class, 'doctorId');
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
        Schema::dropIfExists('therapies');
    }
};
