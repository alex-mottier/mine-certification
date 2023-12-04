<?php

use App\Models\Criteria;
use App\Models\Report;
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
        Schema::create('criteria_report', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Criteria::class)->nullable();
            $table->foreignIdFor(Report::class);
            $table->string('comment');
            $table->double('score')->nullable();
            $table->string('status')->default('for_validation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criteria_report');
    }
};
