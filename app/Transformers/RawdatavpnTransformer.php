<?php

namespace App\Transformers;

use App\Rawdatavpn;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class RawdatavpnTransformer extends TransformerAbstract
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
    public function transform(Rawdatavpn $rawdatavpn)
    {
        return [
            'aid'                 => $rawdatavpn->aid,
            'segment'             => $rawdatavpn->segment,
            'branch_code'         => $rawdatavpn->branch_code,
            'hosp_id'             => $rawdatavpn->hosp_id,
            'pid'                 => $rawdatavpn->pid,
            'birthday'            => $rawdatavpn->birthday,
            'prsn_id'             => $rawdatavpn->prsn_id,
            'cure_stage'          => $rawdatavpn->cure_stage,
            'case_date'           => $rawdatavpn->case_date,
            'iopd_type'           => $rawdatavpn->iopd_type,
            'fami_medic_his'      => $rawdatavpn->fami_medic_his,
            'f_medic_his'         => $rawdatavpn->f_medic_his,
            'm_medic_his'         => $rawdatavpn->m_medic_his,
            'c_medic_his'         => $rawdatavpn->c_medic_his,
            'stage2_yn'           => $rawdatavpn->stage2_yn,
            'diabetes_type'       => $rawdatavpn->diabetes_type,
            'fall_ill_year'       => $rawdatavpn->fall_ill_year,
            'base_chk_date'       => $rawdatavpn->base_chk_date,
            'base_tall'           => $rawdatavpn->base_tall,
            'base_weight'         => $rawdatavpn->base_weight,
            'base_sbp'            => $rawdatavpn->base_sbp,
            'base_ebp'            => $rawdatavpn->base_ebp,
            'blood_chk_date'      => $rawdatavpn->blood_chk_date,
            'blood_hba1c'         => $rawdatavpn->blood_hba1c,
            'blood_ldl'           => $rawdatavpn->blood_ldl,
            'blood_ac'            => $rawdatavpn->blood_ac,
            'blood_pc'            => $rawdatavpn->blood_pc,
            'blood_tg'            => $rawdatavpn->blood_tg,
            'urine_chk_date'      => $rawdatavpn->urine_chk_date,
            'urine_micro'         => $rawdatavpn->urine_micro,
            'urine_routine'       => $rawdatavpn->urine_routine,
            'year_mark'           => $rawdatavpn->year_mark,
            'foot_chk_left'       => $rawdatavpn->foot_chk_left,
            'foot_chk_right'      => $rawdatavpn->foot_chk_right,
            'blood_creat'         => $rawdatavpn->blood_creat,
            'eye_chk8'            => $rawdatavpn->eye_chk8,
            'egfr'                => $rawdatavpn->egfr,
        ];
    }

}
