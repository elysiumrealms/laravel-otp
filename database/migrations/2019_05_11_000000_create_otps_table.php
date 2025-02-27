<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOtpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            config('otp.database.table'),
            function (Blueprint $table) {
                $table->increments('id')->index();
                $table->string('identifier')->index();
                $table->string('token');
                $table->integer('validity');
                $table->boolean('valid')->default(true);
                $table->boolean('status')->default(false);
                $table->timestamps();

                $table->index(['identifier', 'status']);
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('otp.database.table'));
    }
}
