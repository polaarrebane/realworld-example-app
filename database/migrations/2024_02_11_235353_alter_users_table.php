<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    protected const TABLE_NAME = 'users';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->dropColumn('name');
            $table->string('username')->unique();
            $table->text('bio')->default('');
            $table->string('image')->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
            $table->dropColumn('bio');
            $table->dropColumn('image');
            $table->string('name')->nullable();
        });
    }
};
