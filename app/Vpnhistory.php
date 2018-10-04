<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vpnhistory extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'vpnhistory';

    /**
     * 表明模型是否应该被打上时间戳
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['segment', 'branch_code', 'hosp_id', 'pid', 'birthday', 'prsn_id', 'cure_stage', 'case_date', 'iopd_type', 'fami_medic_his', 'f_medic_his', 'm_medic_his', 'c_medic_his', 'stage2_yn', 'diabetes_type', 'fall_ill_year', 'base_chk_date', 'base_tall', 'base_weight', 'base_sbp', 'base_ebp', 'blood_chk_date', 'blood_hba1c', 'blood_ldl', 'blood_ac', 'blood_pc', 'blood_tg', 'urine_chk_date', 'urine_micro', 'urine_routine', 'year_mark', 'foot_chk_left', 'foot_chk_right', 'blood_creat', 'eye_chk8', 'egfr'];

}
