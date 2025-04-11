<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('condition')->comment('0: 良好, 1: 目立った傷や汚れなし, 2: やや傷や汚れあり, 3: 状態が悪い');
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('detail');
            $table->integer('price');
            $table->string('image');
            $table->boolean('is_sold')->default(false)->comment('0: 在庫あり, 1: 在庫なし');
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
        Schema::dropIfExists('items');
    }
}
