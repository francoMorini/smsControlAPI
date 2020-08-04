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
        $company = new Company();
        $company->{ Company::FIELD_NAME } = 'Test Company';
        $company->save();

        $device = new Device();
        $device->{ Device::FIELD_UUID }          = 'abcd1234';
        $device->{ Device::FIELD_DEVICE_NUMBER } = '123456789';
        $device->{ Device::FIELD_COMPANY }       = $company->id;
        $device->{ Device::FIELD_IS_ACTIVE }     = 1;
        $device->save();

        unset( $company );
        unset( $device );

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
