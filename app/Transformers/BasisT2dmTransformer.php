<?php

namespace App\Transformers;

use App\BasisT2dm;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class BasisT2dmTransformer extends TransformerAbstract
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
    public function transform(BasisT2dm $basist2dm)
    {
        return [
            'id'                => $basist2dm->id,
            'basis_id'          => $basist2dm->basis_id,
            'pid'               => $basist2dm->pid,
            'log_date'          => $basist2dm->log_date,
            'fall_ill_year'     => $basist2dm->fall_ill_year,
            'fall_ill_month'    => $basist2dm->fall_ill_month,
            'symptom'           => $basist2dm->symptom,
            'treatment'         => $basist2dm->treatment,
            'medication'        => $basist2dm->medication,
            'medication_year'   => $basist2dm->medication_year,
            'medication_month'  => $basist2dm->medication_month,
            'injection_year'    => $basist2dm->injection_year,
            'injection_month'   => $basist2dm->injection_month,
            'insulin'           => $basist2dm->insulin,
            'longterm'          => $basist2dm->longterm,
            'ineffect'          => $basist2dm->ineffect,
            'shortacting'       => $basist2dm->shortacting,
            'quickeffect'       => $basist2dm->quickeffect,
            'glp1_year'         => $basist2dm->glp1_year,
            'glp1_month'        => $basist2dm->glp1_month,
            'glp1'              => $basist2dm->glp1,
            'continuous'        => $basist2dm->continuous,
            'comorbidity'       => $basist2dm->comorbidity,
            'lung'              => $basist2dm->lung,
            'liver'             => $basist2dm->liver,
            'mental'            => $basist2dm->mental,
            'cancer'            => $basist2dm->cancer,
            'gestation'         => $basist2dm->gestation,
            'stopgestation'     => $basist2dm->stopgestation,
            'complication'      => $basist2dm->complication,
            'heartdisease'      => $basist2dm->heartdisease,
            'blindness'         => $basist2dm->blindness,
            'dialysis'          => $basist2dm->dialysis,
            'amputation'        => $basist2dm->amputation,
            'fami_medic_his'    => $basist2dm->fami_medic_his,
            'relatives'         => $basist2dm->relatives,
            'hypertension'      => $basist2dm->hypertension,
            'cardiovascular'    => $basist2dm->cardiovascular,
            'stroke'            => $basist2dm->stroke,
            'activity'          => $basist2dm->activity,
            'education'         => $basist2dm->education,
            'occupation'        => $basist2dm->occupation,
            'worktime'          => $basist2dm->worktime,
            'fixedtime_from'    => $basist2dm->fixedtime_from,
            'fixedtime_to'      => $basist2dm->fixedtime_to,
            'dayshift_from'     => $basist2dm->dayshift_from,
            'dayshift_to'       => $basist2dm->dayshift_to,
            'middleshift_from'  => $basist2dm->middleshift_from,
            'middleshift_to'    => $basist2dm->middleshift_to,
            'nightshift_from'   => $basist2dm->nightshift_from,
            'nightshift_to'     => $basist2dm->nightshift_to,
            'affectlearning'    => $basist2dm->affectlearning,
            'livingcondition'   => $basist2dm->livingcondition,
            'nursinghome'       => $basist2dm->nursinghome,
            'careunit'          => $basist2dm->careunit,
            'livingtel'         => $basist2dm->livingtel,
            'selfcare'          => $basist2dm->selfcare,
            'caregiver'         => $basist2dm->caregiver,
            'caretel'           => $basist2dm->caretel,
            'sport'             => $basist2dm->sport,
            'sporta1'           => $basist2dm->sporta1,
            'sporta2'           => $basist2dm->sporta2,
            'sportb1'           => $basist2dm->sportb1,
            'sportb2'           => $basist2dm->sportb2,
            'sportc1'           => $basist2dm->sportc1,
            'sportc2'           => $basist2dm->sportc2,
            'sportsum'          => $basist2dm->sportsum,
            'glucometer'        => $basist2dm->glucometer,
            'glucometerfrequency' => $basist2dm->glucometerfrequency,
            'g6pd'              => $basist2dm->g6pd,
            'g6pd_year'         => $basist2dm->g6pd_year,
            'g6pd_month'        => $basist2dm->g6pd_month,
            'closed'            => $basist2dm->closed,
            'close_date'        => $basist2dm->close_date,
            'closed_year'       => $basist2dm->closed_year,
            'closed_month'      => $basist2dm->closed_month,
            'closedcause'       => $basist2dm->closedcause,
            'closedreason'      => $basist2dm->closedreason
        ];
    }

}
