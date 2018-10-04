<?php

namespace App\Transformers;

use App\BasisT1dm;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class BasisT1dm1Transformer extends TransformerAbstract
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
    public function transform(BasisT1dm $basist1dm)
    {
        return [
            'id'                => $basist1dm->id,
            'basis_id'          => $basist1dm->basis_id,
            'pid'               => $basist1dm->pid,
            'log_date'          => $basist1dm->log_date,
            'fall_ill_year'     => $basist1dm->fall_ill_year,
            'fall_ill_month'    => $basist1dm->fall_ill_month,
            'symptom'           => json_decode($basist1dm->symptom),
            'treatment'         => json_decode($basist1dm->treatment),
            'medication'        => json_decode($basist1dm->medication),
            'medication_year'   => $basist1dm->medication_year,
            'medication_month'  => $basist1dm->medication_month,
            'injection_year'    => $basist1dm->injection_year,
            'injection_month'   => $basist1dm->injection_month,
            'insulin'           => $basist1dm->insulin,
            'longterm'          => $basist1dm->longterm,
            'ineffect'          => $basist1dm->ineffect,
            'shortacting'       => $basist1dm->shortacting,
            'quickeffect'       => $basist1dm->quickeffect,
            'glp1_year'         => $basist1dm->glp1_year,
            'glp1_month'        => $basist1dm->glp1_month,
            'glp1'              => $basist1dm->glp1,
            'continuous'        => $basist1dm->continuous,
            'comorbidity'       => json_decode($basist1dm->comorbidity),
            'lung'              => $basist1dm->lung,
            'liver'             => $basist1dm->liver,
            'mental'            => $basist1dm->mental,
            'cancer'            => $basist1dm->cancer,
            'gestation'         => $basist1dm->gestation,
            'stopgestation'     => $basist1dm->stopgestation,
            'complication'      => json_decode($basist1dm->complication),
            'heartdisease'      => json_decode($basist1dm->heartdisease),
            'blindness'         => $basist1dm->blindness,
            'dialysis'          => $basist1dm->dialysis,
            'amputation'        => $basist1dm->amputation,
            'fami_medic_his'    => json_decode($basist1dm->fami_medic_his),
            'relatives'         => json_decode($basist1dm->relatives),
            'hypertension'      => json_decode($basist1dm->hypertension),
            'cardiovascular'    => json_decode($basist1dm->cardiovascular),
            'stroke'            => json_decode($basist1dm->stroke),
            'activity'          => $basist1dm->activity,
            'education'         => $basist1dm->education,
            'occupation'        => $basist1dm->occupation,
            'worktime'          => $basist1dm->worktime,
            'fixedtime_from'    => json_decode($basist1dm->fixedtime_from),
            'fixedtime_to'      => json_decode($basist1dm->fixedtime_to),
            'dayshift_from'     => json_decode($basist1dm->dayshift_from),
            'dayshift_to'       => json_decode($basist1dm->dayshift_to),
            'middleshift_from'  => json_decode($basist1dm->middleshift_from),
            'middleshift_to'    => json_decode($basist1dm->middleshift_to),
            'nightshift_from'   => json_decode($basist1dm->nightshift_from),
            'nightshift_to'     => json_decode($basist1dm->nightshift_to),
            'affectlearning'    => json_decode($basist1dm->affectlearning),
            'livingcondition'   => $basist1dm->livingcondition,
            'nursinghome'       => $basist1dm->nursinghome,
            'careunit'          => $basist1dm->careunit,
            'livingtel'         => $basist1dm->livingtel,
            'selfcare'          => $basist1dm->selfcare,
            'caregiver'         => $basist1dm->caregiver,
            'caretel'           => $basist1dm->caretel,
            'sport'             => $basist1dm->sport,
            'sporta1'           => $basist1dm->sporta1,
            'sporta2'           => $basist1dm->sporta2,
            'sportb1'           => $basist1dm->sportb1,
            'sportb2'           => $basist1dm->sportb2,
            'sportc1'           => $basist1dm->sportc1,
            'sportc2'           => $basist1dm->sportc2,
            'sportsum'          => $basist1dm->sportsum,
            'glucometer'        => $basist1dm->glucometer,
            'glucometerfrequency' => $basist1dm->glucometerfrequency,
            'g6pd'              => $basist1dm->g6pd,
            'g6pd_year'         => $basist1dm->g6pd_year,
            'g6pd_month'        => $basist1dm->g6pd_month,
            'closed'            => $basist1dm->closed,
            'close_date'        => $basist1dm->close_date,
            'closed_year'       => $basist1dm->closed_year,
            'closed_month'      => $basist1dm->closed_month,
            'closedcause'       => $basist1dm->closedcause,
            'closedreason'      => $basist1dm->closedreason
        ];
    }

}
