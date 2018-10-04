<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cases extends Model
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
        'basis_id', 'hosp_id', 'pid', 'cure_stage', 'case_date', 'prsn_id', 'educator', 'base_tall', 'base_weight', 'base_sbp',
        'base_ebp', 'pulse', 'noweight', 'ibw', 'base_bmi', 'waist', 'hips', 'blood_mne', 'blood_ap', 'blood_mins', 'blood_hba1c',
        'blood_ldl', 'blood_acpc', 'cholesterol', 'blood_tg', 'hdl', 'gpt', 'blood_creat', 'uricacid', 'urine_micro', 'upcr',
        'urine_routine', 'egfr', 'foot_chk_left', 'foot_chk_right', 'ulcers', 'ulcers_urgent', 'ulcers_slow', 'kidneydisease', 'kidneydisease_stage',
        'intermittentpain', 'abi', 'abi_right', 'abi_left', 'cavi', 'cavi_right', 'cavi_left', 'cavi_rkcavi',
        'eye_chk8', 'eye_chk8_right', 'eye_chk8_left', 'cataract', 'ecg', 'ecgitem', 'ecgother', 'coronaryheart', 'coronaryheart_item',
        'coronaryheart_other', 'chh_year', 'chh_month', 'stroke', 'stroke_other', 'sh_year', 'sh_month', 'blindness',
        'blindness_right', 'blindness_left', 'bh_year', 'bh_month', 'dialysis', 'dialysis_item', 'dh_year', 'dh_month', 'amputation',
        'amputation_right', 'amputation_left', 'ah_year', 'ah_month', 'amputation_other', 'medicaltreatment', 'medicaltreatment_times',
        'drinking', 'drinkingother', 'periodontal', 'masticatory', 'checker', 'smoking', 'havesmoke', 'quitsmoke', 'followdisease',
        'close_date', 'laboratory', 'bgmachine', 'machine_blood', 'machine_use', 'machine_exception', 'machine_other', 'neuropathy', 'vascularlesion'
    ];

}
