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
        $consumerModel = new Consumer();
        $consumerModel->{ Consumer::FIELD_NAME }       = 'Default Costumer';
        $consumerModel->{ Consumer::FIELD_SECRET_KEY } = 'W{`A_C+dVvP$s5%]/E"g:]K3?Zm%`K';
        $consumerModel->save();
        unset( $consumerModel );

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
