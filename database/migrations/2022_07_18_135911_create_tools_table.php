<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToolsTable extends Migration
{
    public function up()
    {
        Schema::create("tools", function (Blueprint $table) {
            $table->id();
            $table->string("title")->unique();
            $table->string("link");
            $table->string("description");
            $table->json("tags");
        });
    }

    public function down()
    {
        Schema::dropIfExists("tools");
    }
}
