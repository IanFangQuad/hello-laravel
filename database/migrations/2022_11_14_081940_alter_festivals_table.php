<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('festivals');

        if (!Schema::hasTable('festivals')) {
            Schema::create('festivals', function (Blueprint $table) {
                $table->id();
                $table->date('date');
                $table->string('weekName');
                $table->boolean('dayoff');
                $table->text('annotation')->nullable();
                $table->timestamps();
            });
        }
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
};
