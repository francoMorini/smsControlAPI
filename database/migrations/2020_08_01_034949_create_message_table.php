<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Message;

class CreateMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create( 'messages', function ( Blueprint $table ) {

            $table->engine = 'InnoDB';

            $table->id();
            $table->string( Message::FIELD_INTERNAL_SMS_ID, 13 )->nullable();
            $table->string( Message::FIELD_MESSAGE, 150 );
            $table->string( Message::FIELD_CLIENT_NUMBER, 16 );

            $table->unsignedBigInteger( Message::FIELD_DEVICE_ID );
            $table->foreign( Message::FIELD_DEVICE_ID )->references( 'id' )->on( 'devices' );

            $table->boolean( Message::FIELD_FROM_US_TO_CLIENT );
            $table->tinyInteger( Message::FIELD_STATUS );
            $table->dateTime( Message::FIELD_SEND_DATE, 0 );
            $table->timestamps();

        } );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'messages' );
    }
}
