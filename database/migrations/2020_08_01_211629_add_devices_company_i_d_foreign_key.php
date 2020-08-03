<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Device;

class AddDevicesCompanyIDForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table( 'devices', function ( Blueprint $table ) {

            $table->foreign( Device::FIELD_COMPANY )->references( 'id' )->on( 'companies' );

        } );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table( 'devices', function ( Blueprint $table ) {

            $table->dropForeign('devices_' . Device::FIELD_COMPANY . '_foreign');

        } );

    }
}
