<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('suricata_alerts', function (Blueprint $table) {
        $table->id('src_id');
        $table->string('message');
        $table->string('src_ip');
        $table->string('dest_ip');
        $table->string('protocol');
        $table->integer('priority')->nullable();
        $table->timestamps();
    });
}
    public function down()
{
    Schema::dropIfExists('suricata_alerts');
}

};
