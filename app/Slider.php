<?php

namespace App;

use App\Traits\CommonModel;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model {
	protected $guarded = ['id'];
	use CommonModel;
}
