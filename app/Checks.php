<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checks extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'checks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['hosp_id', 'check_date', 'name', 'pid', 'birthday', 'sex', 'items', 'values', 'serialno', 'print_date'];

}
