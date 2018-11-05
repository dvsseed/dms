<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mrecords extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'mrecords';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'basis_id', 'hosp_id', 'pid', 'add_user_id', 'educator_user_id', 'soap_date', 'close_date'
    ];

}
