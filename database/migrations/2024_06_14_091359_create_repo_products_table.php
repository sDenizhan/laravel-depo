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
        Schema::create('repo_has_products', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('source_id')->unsigned(); // 0 = Supplier | >0 = Other Repos
            $table->bigInteger('target_id')->unsigned(); // repos

            $table->bigInteger('product_id')->unsigned();
            $table->integer('quantity')->default(0);

            $table->bigInteger('action_by')->unsigned();
            $table->timestamp('action_at')->useCurrent();
            $table->string('action_note')->nullable();

            $table->foreign('action_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('target_id')->references('id')->on('repos')->onDelete('cascade');
            $table->foreign('source_id')->references('id')->on('repos')->onDelete('cascade');
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
        Schema::dropIfExists('repo_has_products');
    }
};
