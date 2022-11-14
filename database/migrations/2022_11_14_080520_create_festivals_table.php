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
        Schema::drop('leaves');

        if (!Schema::hasTable('leaves')) {
            Schema::create('leaves', function (Blueprint $table) {
                $table->id();
                $table->integer('member_id');
                $table->string('type');
                $table->timestamp('start');
                $table->timestamp('end');
                $table->text('description')->nullable();
                $table->boolean('approval');
                $table->integer('hours')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('festivals')) {
            Schema::create('festivals', function (Blueprint $table) {
                $table->id();
                $table->timestamp('date');
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
