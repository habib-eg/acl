<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('method',15);
            $table->text('roles')->nullable();
            $table->text('middleware')->nullable();
            $table->text('permissions')->nullable();
            $table->boolean('is_public')->default(false);
            $table->boolean('is_disable')->default(false);
            $table->boolean('auth')->default(false);
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
    }
}
