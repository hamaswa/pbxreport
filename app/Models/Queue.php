<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Queue
 * @package App\Models
 * @version Jan 02, 2019, 9:51 am UTC
 *
 * @property integer id
 * @property integer queue
 * @property  queue_description
 */
class Queue extends Model
{

    public $table = 'queue';





    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     /
    protected $casts = [
        'id'=>'integer',
        'queue' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     /
    public static $rules = [
        'queue' => 'required',
        'queue_description' => 'required'
    ];
     */

    public function user()
	{
		return $this->belongTo('App\Models\User');
	}

}
