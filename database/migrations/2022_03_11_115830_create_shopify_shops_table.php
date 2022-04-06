<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('shopify_shops', function (Blueprint $table) : void {
            $table->unsignedBigInteger('id')->primary();
            $table->string('url')->unique();
            $table->string('access_token')->nullable();
            $table->string('status')->default('active');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('domain')->nullable();
            $table->boolean('dev')->default(false);
            $table->boolean('plus')->default(false);
            $table->string('scope')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @codeCoverageIgnore
     */
    public function down() : void
    {
        Schema::dropIfExists('shopify_shops');
    }
};
