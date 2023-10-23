<?php

use App\Models\Institution;
use App\Models\Mine;
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
        Schema::create('institution_mine', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Institution::class);
            $table->foreignIdFor(Mine::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institution_mine');
    }
};
