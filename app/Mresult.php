<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mresult extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'mresult';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['basis_id', 'hosp_id', 'pid', 'mr_date', 'mr_time', 'slot', 'slotname', 'value1', 'value2', 'value3', 'stype', 'svalue', 'btype', 'note', 'autonote', 'mdataid', 'modifyid', 'diaryid'];

}
