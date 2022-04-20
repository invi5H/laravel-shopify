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
            $table->string('storefront_token')->nullable();
            $table->string('status')->default('active');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('domain')->nullable();
            $table->boolean('dev')->default(false);
            $table->boolean('plus')->default(false);
            // for small apps, the default of 255 should be enough, for big apps 511 will be enough, but 1023 should cover edge cases too
            // asking for every public scope in shopify currently goes around ~700
            $table->string('scope', 1023)->nullable();
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
