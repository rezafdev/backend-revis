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
        Schema::create('therapy_photos', function (Blueprint $table) {
            $table->id();
            $table->string('path')->nullable(false);
            $table->foreignIdFor(Therapy::class, 'therapyId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('therapy_photos');
    }
};
