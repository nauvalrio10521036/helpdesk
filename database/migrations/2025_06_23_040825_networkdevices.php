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
        Schema::create('networkdevices', function (Blueprint $table) {
            $table->id('device_id');
            $table->string('name');
            $table->enum('tipe', ['access_point', 'switch_poe', 'mikrotik', 'switch_hub']);
            $table->string('ip_address');
            $table->string('mac_address')->unique();
            $table->unsignedBigInteger('vlan_id');
            $table->foreign('vlan_id')->references('vlan_id')->on('vlan')->onDelete('cascade');
            $table->string('lokasi');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('tidak_aktif');
            $table->timestamps();

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
