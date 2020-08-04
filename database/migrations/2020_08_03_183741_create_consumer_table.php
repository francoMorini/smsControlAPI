<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use App\Consumer;

class CreateConsumerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('consumers', function (Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->increments( 'id' );
            $table->string( 'name', 16 );
            $table->string( 'secret_key', 255 )->unique();
            $table->integer( 'api_usage' )->default( 0 );
            $table->dateTime( 'last_api_usage', 0 )->nullable();
            $table->timestamps();

        });

        // For testing
        $consumer = new Consumer();
        $consumer->{ Consumer::FIELD_NAME }       = 'Client Costumer';
        $consumer->{ Consumer::FIELD_SECRET_KEY } = 's?v.m9H}^Em]ZBu)toGt12b0tf6Cmd';
        $consumer->save();

        $consumer = new Consumer();
        $consumer->{ Consumer::FIELD_NAME }       = 'Your Devices';
        $consumer->{ Consumer::FIELD_SECRET_KEY } = '.Em~@@lN4!TNI,s9pB^x4-~aCKvb-J';
        $consumer->save();

        unset( $consumer );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consumers');
    }
}
