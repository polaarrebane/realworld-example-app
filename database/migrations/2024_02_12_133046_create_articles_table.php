<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected const string TABLE_NAME = 'articles';

    protected const string USERS_TABLE_NAME = 'users';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {

            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->text('description');
            $table->text('body');
            $table->string('slug')->unique();

            $table->unsignedBigInteger('author_id')->index();
            $table->foreign('author_id')
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
