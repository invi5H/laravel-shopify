<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->unique();
            $table->unsignedBigInteger('shopify_id')->unique();
            $table->string('shopify_token')->nullable()->index();
            $table->string('shop_name')->nullable();
            $table->string('shop_email')->nullable();
            $table->string('admin_name')->nullable();
            $table->string('admin_email')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('primary_currency')->nullable();
            $table->string('origin_country')->nullable();
            $table->string('primary_language')->default('en');
            $table->unsignedBigInteger('primary_location_id')->nullable();
            $table->string('shopify_plan')->nullable();
            $table->string('shopify_plan_display_name')->nullable();
            $table->string('scope');
            $table->string('target_scope')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
