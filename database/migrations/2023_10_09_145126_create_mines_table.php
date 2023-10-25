<?php

use App\Models\User;
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
        Schema::create('mines', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('email');
            $table->string('phone_number');
            $table->string('tax_number');

            $table->string('status')->default('created');

            $table->double('longitude');
            $table->double('latitude');

            $table->foreignIdFor(User::class,'created_by')->nullable();
            $table->foreignIdFor(User::class,'validated_by')->nullable();
            $table->timestamp('validated_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mines');
    }
};
