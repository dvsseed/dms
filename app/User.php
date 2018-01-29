<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Vinkla\Hashids\Facades\Hashids;

/**
 * App\User
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property string $institution
 * @property string $hosp_id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $pid
 * @property integer $role_level
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereClinic($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRoleLevel($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Post[] $posts
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User admin()
 * @method static \Illuminate\Database\Query\Builder|\App\User notAdmin()
 * @property-read mixed $avatar
 * @property-read \App\Role $role
 * @property-read mixed $hashid
 * @property string $bio
 * @method static \Illuminate\Database\Query\Builder|\App\User whereBio($value)
 */
class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'institution', 'hosp_id', 'name', 'username', 'email', 'bio', 'pid', 'role_level'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    //RELATIONS
    /**
     *
     * 获取关联到用户的選單權限
     */
//    public function menu(){
//        return $this->hasOne(UserMenu::class, 'user_id');
//    }
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_level', 'level');
    }

    // Related attributes
    public function isAdmin()
    {
        return $this->role_level == 9;
    }

    public function isPatient()
    {
        return $this->role_level == 1;
    }

    public function owns(Post $post)
    {
        return $this->id == $post->created_by;
    }

    //SCOPES
    public function scopeAdmin()
    {
        return $this->where('role_level', 9);
    }

    public function scopeNotAdmin()
    {
        return $this->where('role_level', '!=', 9);
    }

    //Mutators
    public function getAvatarAttribute()
    {
        return 'https://www.gravatar.com/avatar/' . md5($this->email);
    }

    public function getHashidAttribute()
    {
        return Hashids::encode($this->id);
    }
}
