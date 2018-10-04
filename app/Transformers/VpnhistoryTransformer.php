<?php

namespace App\Transformers;

use App\Vpnhistory;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class VpnhistoryTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var  array
     */
    protected $availableIncludes = [];

    /**
     * List of resources to automatically include
     *
     * @var  array
     */
    protected $defaultIncludes = [];

    /**
     * Transform object into a generic array
     *
     * @var  object
     * @return array
     */
    public function transform(Vpnhistory $vpnhistory)
    {
        return [
            'aid'                 => $vpnhistory->aid,
            'segment'             => $vpnhistory->segment,
            'branch_code'         => $vpnhistory->branch_code,
            'hosp_id'             => $vpnhistory->hosp_id,
            'pid'                 => $vpnhistory->pid,
            'birthday'            => $vpnhistory->birthday,
            'prsn_id'             => $vpnhistory->prsn_id,
            'cure_stage'          => $vpnhistory->cure_stage,
            'case_date'           => $vpnhistory->case_date,
            'iopd_type'           => $vpnhistory->iopd_type,
            'fami_medic_his'      => $vpnhistory->fami_medic_his,
            'f_medic_his'         => $vpnhistory->f_medic_his,
            'm_medic_his'         => $vpnhistory->m_medic_his,
            'c_medic_his'         => $vpnhistory->c_medic_his,
            'stage2_yn'           => $vpnhistory->stage2_yn,
            'diabetes_type'       => $vpnhistory->diabetes_type,
            'fall_ill_year'       => $vpnhistory->fall_ill_year,
            'base_chk_date'       => $vpnhistory->base_chk_date,
            'base_tall'           => $vpnhistory->base_tall,
            'base_weight'         => $vpnhistory->base_weight,
            'base_sbp'            => $vpnhistory->base_sbp,
            'base_ebp'            => $vpnhistory->base_ebp,
            'blood_chk_date'      => $vpnhistory->blood_chk_date,
            'blood_hba1c'         => $vpnhistory->blood_hba1c,
            'blood_ldl'           => $vpnhistory->blood_ldl,
            'blood_ac'            => $vpnhistory->blood_ac,
            'blood_pc'            => $vpnhistory->blood_pc,
            'blood_tg'            => $vpnhistory->blood_tg,
            'urine_chk_date'      => $vpnhistory->urine_chk_date,
            'urine_micro'         => $vpnhistory->urine_micro,
            'urine_routine'       => $vpnhistory->urine_routine,
            'year_mark'           => $vpnhistory->year_mark,
            'foot_chk_left'       => $vpnhistory->foot_chk_left,
            'foot_chk_right'      => $vpnhistory->foot_chk_right,
            'blood_creat'         => $vpnhistory->blood_creat,
            'eye_chk8'            => $vpnhistory->eye_chk8,
            'egfr'                => $vpnhistory->egfr,
        ];
    }

}
