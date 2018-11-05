<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Soaps extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'soaps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'basis_id', 'mrecords_id', 'hosp_id', 'pid', 'educator_user_id', 'soap_date', 'S', 'O', 'A', 'P'
    ];

}
