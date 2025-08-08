<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('report', function (Blueprint $table) {
            $table->id('report_id');
            $table->string('title');
            $table->text('description');
            $table->string('lokasi');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('device_id')->nullable();
            $table->enum('status', ['waiting', 'process', 'finished']);
            $table->enum("prioritas", ['low', 'medium', 'high'])->default('low');
            $table->string('attachment')->nullable();
            $table->timestamp('time_report')->useCurrent();
            $table->timestamp('time_finished')->nullable();
            $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');
            $table->foreign('device_id')->references('device_id')->on('networkdevices')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
