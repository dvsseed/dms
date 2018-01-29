<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mrnote extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'mrnote';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['hosp_id', 'pid', 'mr_date', 'note', 'educator'];

}
