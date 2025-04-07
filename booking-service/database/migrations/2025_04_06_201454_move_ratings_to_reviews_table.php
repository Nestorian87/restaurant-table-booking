<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedTinyInteger('rating_kitchen')->nullable();
            $table->unsignedTinyInteger('rating_interior')->nullable();
            $table->unsignedTinyInteger('rating_service')->nullable();
            $table->unsignedTinyInteger('rating_atmosphere')->nullable();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'rating_kitchen',
                'rating_interior',
                'rating_service',
                'rating_atmosphere',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedTinyInteger('rating_kitchen')->nullable();
            $table->unsignedTinyInteger('rating_interior')->nullable();
            $table->unsignedTinyInteger('rating_service')->nullable();
            $table->unsignedTinyInteger('rating_atmosphere')->nullable();
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn([
                'rating_kitchen',
                'rating_interior',
                'rating_service',
                'rating_atmosphere',
            ]);
        });
    }
};
