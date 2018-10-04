<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Doctor
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property string $hosp_id
 * @property string $name
 * @property string $pid
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Doctor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_code', 'hosp_id', 'name', 'pid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

}
