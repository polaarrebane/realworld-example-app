<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected const string TABLE_NAME = 'comments';

    protected const string USERS_TABLE_NAME = 'users';

    protected const string ARTICLES_TABLE_NAME = 'articles';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('body');

            $table->unsignedBigInteger('author_id')->index();
            $table->foreign('author_id')
                ->references('id')
                ->on(self::USERS_TABLE_NAME)
                ->onDelete('cascade');

            $table->unsignedBigInteger('article_id')->index();
            $table->foreign('article_id')
                ->references('id')
                ->on(self::ARTICLES_TABLE_NAME)
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
