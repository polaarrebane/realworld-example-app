<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected const string TABLE_NAME = 'follow';

    protected const string USERS_TABLE_NAME = 'users';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('following_id')->index();

            $table->foreign('user_id')
                ->references('id')
                ->on(self::USERS_TABLE_NAME)
                ->onDelete('cascade');

            $table->foreign('following_id')
                ->references('id')
                ->on(self::USERS_TABLE_NAME)
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
