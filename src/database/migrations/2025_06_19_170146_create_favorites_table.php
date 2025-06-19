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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            // お気に入りをするユーザー
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // お気に入りされるユーザー
            $table->foreignId('favorite_user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // user_id と favorite_user_id の組み合わせをユニークにして重複登録を防ぐ
            $table->unique(['user_id', 'favorite_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
