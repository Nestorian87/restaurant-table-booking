<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('table_types', function (Blueprint $table) {
            $table->foreign('restaurant_id')
                ->references('id')->on('restaurants')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('table_types', function (Blueprint $table) {
            $table->dropForeign(['restaurant_id']);
        });
    }
};
