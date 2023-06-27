<?php

use App\Models\Therapy;
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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('surname')->nullable(false);
            $table->tinyInteger('sexType')->nullable();
            $table->date('birthday')->nullable();
            $table->string('email')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('phoneCountryCode')->nullable();
            $table->string('phoneNumber')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
