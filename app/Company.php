<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    
	const FIELD_NAME      = 'name';
	const FIELD_IS_ACTIVE = 'is_active';
	const FIELD_LAST_USED = 'last_used';

	protected $table = 'companies';

}
