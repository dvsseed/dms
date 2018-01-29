<?php

namespace App;

//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Cases2 extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'cases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'case_date', 'name', 'pid', 'cure_stage'
    ];

    public function set($data) {
        foreach ($data as $key => $value) {
            array_add(this, $key, $value);
        }
    }
}
