<?php

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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

        $table->string('nom');
        $table->string('slug')->unique();

        $table->string('email')->nullable();
        $table->string('telephone')->nullable();

        $table->string('pays', 2)->nullable();
        $table->char('Devise', 3)->default('XOF');
        $table->string('timezone')->default('Africa/Abidjan');

        $table->string('status')->default('active');

        $table->timestamps();
        $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
