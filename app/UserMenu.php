<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property tinyint menu1
 * @property tinyint menu2
 * @property tinyint menu3
 * @property tinyint menu4
 * @property tinyint menu5
 * @property tinyint menu6
 * @property tinyint menu7
 * @property tinyint menu8
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class UserMenu extends Authenticatable
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'user_menu';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['menu1', 'menu2', 'menu3', 'menu4', 'menu5', 'menu6', 'menu7', 'menu8'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * 获取選單对应的用户
     *
     * @var array
     */
    public function user() {
        return $this->belongsTo('App\User');
    }
}
