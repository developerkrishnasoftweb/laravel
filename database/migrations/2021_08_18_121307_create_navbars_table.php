<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNavbarsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('navbars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('url')->nullable();
            $table->text('icon')->nullable();
            $table->integer('parent_nav_id')->default(0);
            $table->string('nav_type', 10)->default('menu');
            $table->integer('position')->nullable();
            $table->string('status', 10)->default('y');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('navbars');
    }
}
