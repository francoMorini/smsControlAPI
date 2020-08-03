<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Company;
use App\Device;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create( 'companies', function ( Blueprint $table ) {

            $table->engine = 'InnoDB';

            $table->tinyIncrements( 'id' );
            $table->string( Company::FIELD_NAME );
            $table->boolean( Company::FIELD_IS_ACTIVE )->default( 0 );
            $table->dateTime( Company::FIELD_LAST_USED, 0 )->nullable();
            $table->timestamps();

        } );

        // Company and device for test
        $companyModel = new Company();
        $companyModel->{ Company::FIELD_NAME } = 'Test Company';
        $companyModel->save();

        $deviceModel = new Device();
        $deviceModel->{ Device::FIELD_UUID }          = 'abcd1234';
        $deviceModel->{ Device::FIELD_DEVICE_NUMBER } = '123456789';
        $deviceModel->{ Device::FIELD_COMPANY }       = $companyModel->id;
        $deviceModel->save();

        unset( $companyModel );
        unset( $deviceModel );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'companies' );
    }
}
