<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected const string TABLE_NAME = 'article_tag';

    protected const string ARTICLES_TABLE_NAME = 'articles';

    protected const string TAGS_TABLE_NAME = 'tags';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('article_id')->index();
            $table->unsignedBigInteger('tag_id')->index();

            $table->foreign('article_id')
                ->references('id')
                ->on(self::ARTICLES_TABLE_NAME)
                ->onDelete('cascade');

            $table->foreign('tag_id')
                ->references('id')
                ->on(self::TAGS_TABLE_NAME)
                ->onDelete('cascade');

            $table->index(['article_id', 'tag_id']);
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
