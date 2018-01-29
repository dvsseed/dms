<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bsrange extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'bsrange';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['basis_id', 'hosp_id', 'pid', 'deepnightlow', 'deepnighthigh', 'weekuplow', 'weekuphigh', 'beforebreaklow', 'beforebreakhigh', 'afterbreaklow', 'afterbreakhigh', 'beforenoonlow', 'beforenoonhigh', 'afternoonlow', 'afternoonhigh', 'beforedinnerlow', 'beforedinnerhigh', 'afterdinnerlow', 'afterdinnerhigh', 'sleeplow', 'sleephigh', 'deepnightftime', 'deepnightttime', 'weekupftime', 'weekupttime', 'beforebreakftime', 'beforebreakttime', 'afterbreakftime', 'afterbreakttime', 'beforenoonftime', 'beforenoonttime', 'afternoonftime', 'afternoonttime', 'beforedinnerftime', 'beforedinnerttime', 'afterdinnerftime', 'afterdinnerttime', 'sleepftime', 'sleepttime'];

}
