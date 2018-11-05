<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicalrecords extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'medicalrecords';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'pid', 'mrdate', 'start_date', 'soap_date', 'ename', 'sname'
    ];

}
