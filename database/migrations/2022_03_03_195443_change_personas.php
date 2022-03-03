<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePersonas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('personas', function (Blueprint $table) {
            $table->string('documento')->nullable()->change();
            $table->string('sexo')->nullable()->change();
            $table->string('origen')->nullable()->change();
            $table->dateTime('fecha_nacimiento')->nullable()->change();
            $table->dateTime('fecha_recepcion_muestra')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
