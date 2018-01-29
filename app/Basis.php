<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Basis extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'basis';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['hosp_id', 'medical_number', 'name', 'birthday', 'pid', 'sex', 'tel1', 'tel2', 'company_tel', 'mobile1', 'mobile2', 'inform_addr', 'emergency_contact', 'emergency_relationship', 'emergency_relationship_other', 'emergency_tel', 'emergency_mobile', 'language', 'language_other', 'drug_allergy', 'drug_allergy_insulin', 'drug_allergy_other'];

    public function cases()
    {
        return $this->hasMany(Cases::class);
    }

}
