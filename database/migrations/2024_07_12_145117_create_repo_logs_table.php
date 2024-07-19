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
        Schema::create('repo_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('repo_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();

            $table->integer('count')->default(0);
            $table->enum('action', ['in', 'out']);
            $table->json('data')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('repo_id')->references('id')->on('repos')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repo_logs');
    }
};
