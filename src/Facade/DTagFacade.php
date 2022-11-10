<?php
namespace Dob\DTag\Facade;

use Illuminate\Support\Facades\Facade;

class DTagFacade extends Facade {

	protected static function getFacadeAccessor() { return 'dtag'; }

}