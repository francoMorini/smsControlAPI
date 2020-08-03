<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Device;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create( 'devices', function ( Blueprint $table ) {

            $table->engine = 'InnoDB';

            $table->id();
            $table->string( Device::FIELD_UUID, 32 );
            $table->string( Device::FIELD_DEVICE_NUMBER, 16 )->unique();
            $table->unsignedTinyInteger( Device::FIELD_COMPANY );
            $table->boolean( Device::FIELD_IS_ACTIVE )->default( 0 );
            $table->dateTime( Device::FIELD_LAST_USED_DATE, 0 )->nullable();
            $table->tinyInteger( Device::FIELD_BATTERY_LEVEL )->nullable();
            $table->boolean( Device::FIELD_IS_PLUGGED_IN )->nullable();
            $table->string( Device::FIELD_NETWORK, 4 )->nullable();
            $table->dateTime( Device::FIELD_LAST_REPORT, 0 )->nullable();
            $table->boolean( Device::FIELD_MAIL_SENT_BATTERY )->default( 0 );
            $table->dateTime( Device::FIELD_DEVICE_PLUGGED_OFF, 0 )->nullable();
            $table->boolean( Device::FIELD_MAIL_SENT_PLUGGED_OFF )->default( 0 );
            $table->boolean( Device::FIELD_MAIL_SENT_INACIVITY )->default( 0 );
            $table->timestamps();

            $table->unique( [ 'uuid', 'number' ] );

        } );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
