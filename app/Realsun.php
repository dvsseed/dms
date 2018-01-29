<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Realsun extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'realsun';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['hosp_id', 'drugcode', 'check_date', 'checkno', 'name', 'birthday', 'pid', 'tel', 'diseasename', 'prescriptiondose', 'expirydate'];

}
