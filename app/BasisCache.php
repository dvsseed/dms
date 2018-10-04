<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BasisCache extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'basis_cache';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'pid', 'birthday'];

}
