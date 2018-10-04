<?php

namespace App\Transformers;

use App\Cases;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class Cases1Transformer extends TransformerAbstract
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
    public function transform(Cases $cases)
    {
        return [
            'id'                 => $cases->id,
            'basis_id'           => $cases->basis_id,
            'hosp_id'            => $cases->hosp_id,
            'pid'                => $cases->pid,
            'cure_stage'         => $cases->cure_stage,
            'case_date'          => $cases->case_date,
            'prsn_id'            => $cases->prsn_id,
            'educator'           => $cases->educator,
            'base_tall'          => $cases->base_tall,
            'base_weight'        => $cases->base_weight,
            'base_sbp'           => $cases->base_sbp,
            'base_ebp'           => $cases->base_ebp,
            'pulse'              => $cases->pulse,
            'noweight'           => $cases->noweight,
            'ibw'                => $cases->ibw,
            'base_bmi'           => $cases->base_bmi,
            'waist'              => $cases->waist,
            'hips'               => $cases->hips,
            'blood_mne'          => $cases->blood_mne,
            'blood_ap'           => $cases->blood_ap,
            'blood_mins'         => $cases->blood_mins,
            'blood_hba1c'        => $cases->blood_hba1c,
            'blood_ldl'          => $cases->blood_ldl,
            'blood_acpc'         => $cases->blood_acpc,
            'cholesterol'        => $cases->cholesterol,
            'blood_tg'           => $cases->blood_tg,
            'hdl'                => $cases->hdl,
            'gpt'                => $cases->gpt,
            'blood_creat'        => $cases->blood_creat,
            'uricacid'           => $cases->uricacid,
            'urine_micro'        => $cases->urine_micro,
            'upcr'               => $cases->upcr,
            'urine_routine'      => $cases->urine_routine,
            'egfr'               => $cases->egfr,
            'foot_chk_left'      => json_decode($cases->foot_chk_left),
            'foot_chk_right'     => json_decode($cases->foot_chk_right),
            'ulcers'             => json_decode($cases->ulcers),
            'ulcers_urgent'      => $cases->ulcers_urgent,
            'ulcers_slow'        => $cases->ulcers_slow,
            'kidneydisease'      => $cases->kidneydisease,
            'kidneydisease_stage' => $cases->kidneydisease_stage,
            'intermittentpain'   => json_decode($cases->intermittentpain),
            'abi'                => json_decode($cases->abi),
            'abi_right'          => $cases->abi_right,
            'abi_left'           => $cases->abi_left,
            'cavi'               => json_decode($cases->cavi),
            'cavi_right'         => $cases->cavi_right,
            'cavi_left'          => $cases->cavi_left,
            'cavi_rkcavi'        => $cases->cavi_rkcavi,
            'eye_chk8'           => json_decode($cases->eye_chk8),
            'eye_chk8_right'     => $cases->eye_chk8_right,
            'eye_chk8_left'      => $cases->eye_chk8_left,
            'cataract'           => json_decode($cases->cataract),
            'ecg'                => json_decode($cases->ecg),
            'ecgitem'            => $cases->ecgitem,
            'ecgother'           => $cases->ecgother,
            'coronaryheart'      => json_decode($cases->coronaryheart),
            'coronaryheart_item'  => $cases->coronaryheart_item,
            'coronaryheart_other' => $cases->coronaryheart_other,
            'chh_year'           => $cases->chh_year,
            'chh_month'          => $cases->chh_month,
            'stroke'             => json_decode($cases->stroke),
            'stroke_other'       => $cases->stroke_other,
            'sh_year'            => $cases->sh_year,
            'sh_month'           => $cases->sh_month,
            'blindness'          => json_decode($cases->blindness),
            'blindness_right'    => $cases->blindness_right,
            'blindness_left'     => $cases->blindness_left,
            'bh_year'            => $cases->bh_year,
            'bh_month'           => $cases->bh_month,
            'dialysis'           => json_decode($cases->dialysis),
            'dialysis_item'      => $cases->dialysis_item,
            'dh_year'            => $cases->dh_year,
            'dh_month'           => $cases->dh_month,
            'amputation'         => json_decode($cases->amputation),
            'amputation_right'   => $cases->amputation_right,
            'amputation_left'    => $cases->amputation_left,
            'ah_year'            => $cases->ah_year,
            'ah_month'           => $cases->ah_month,
            'amputation_other'   => $cases->amputation_other,
            'medicaltreatment'   => json_decode($cases->medicaltreatment),
            'medicaltreatment_times' => $cases->medicaltreatment_times,
            'drinking'           => json_decode($cases->drinking),
            'drinkingother'      => $cases->drinkingother,
            'periodontal'        => $cases->periodontal,
            'masticatory'        => $cases->masticatory,
            'checker'            => $cases->checker,
            'smoking'            => $cases->smoking,
            'havesmoke'          => $cases->havesmoke,
            'quitsmoke'          => $cases->quitsmoke,
            'followdisease'      => json_decode($cases->followdisease),
            'close_date'         => $cases->close_date,
            'laboratory'         => $cases->laboratory,
            'bgmachine'          => $cases->bgmachine,
            'machine_blood'      => $cases->machine_blood,
            'machine_use'        => $cases->machine_use,
            'machine_exception'  => $cases->machine_exception,
            'machine_other'      => $cases->machine_other,
            'neuropathy'         => $cases->neuropathy,
            'vascularlesion'     => $cases->vascularlesion,


        ];
    }

}
