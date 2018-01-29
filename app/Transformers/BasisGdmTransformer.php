<?php

namespace App\Transformers;

use App\BasisGdm;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class BasisGdmTransformer extends TransformerAbstract
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
    public function transform(BasisGdm $basisgdm)
    {
        return [
            'id'                => $basisgdm->id,
            'basis_id'          => $basisgdm->basis_id,
            'pid'               => $basisgdm->pid,
            'log_date'          => $basisgdm->log_date,
            'fall_ill_year'     => $basisgdm->fall_ill_year,
            'fall_ill_month'    => $basisgdm->fall_ill_month,
            'currentweeks'      => $basisgdm->currentweeks,
            'maternitynumber'   => $basisgdm->maternitynumber,
            'gestationaldiabetes' => $basisgdm->gestationaldiabetes,
            'symptom'           => $basisgdm->symptom,
            'treatment'         => $basisgdm->treatment,
            'medication'        => $basisgdm->medication,
            'medication_year'   => $basisgdm->medication_year,
            'medication_month'  => $basisgdm->medication_month,
            'injection_year'    => $basisgdm->injection_year,
            'injection_month'   => $basisgdm->injection_month,
            'insulin'           => $basisgdm->insulin,
            'longterm'          => $basisgdm->longterm,
            'ineffect'          => $basisgdm->ineffect,
            'shortacting'       => $basisgdm->shortacting,
            'quickeffect'       => $basisgdm->quickeffect,
            'glp1_year'         => $basisgdm->glp1_year,
            'glp1_month'        => $basisgdm->glp1_month,
            'glp1'              => $basisgdm->glp1,
            'comorbidity'       => $basisgdm->comorbidity,
            'lung'              => $basisgdm->lung,
            'liver'             => $basisgdm->liver,
            'mental'            => $basisgdm->mental,
            'cancer'            => $basisgdm->cancer,
            'gestation'         => $basisgdm->gestation,
            'stopgestation'     => $basisgdm->stopgestation,
            'fami_medic_his'    => $basisgdm->fami_medic_his,
            'relatives'         => $basisgdm->relatives,
            'hypertension'      => $basisgdm->hypertension,
            'cardiovascular'    => $basisgdm->cardiovascular,
            'stroke'            => $basisgdm->stroke,
            'activity'          => $basisgdm->activity,
            'education'         => $basisgdm->education,
            'occupation'        => $basisgdm->occupation,
            'worktime'          => $basisgdm->worktime,
            'fixedtime_from'    => $basisgdm->fixedtime_from,
            'fixedtime_to'      => $basisgdm->fixedtime_to,
            'dayshift_from'     => $basisgdm->dayshift_from,
            'dayshift_to'       => $basisgdm->dayshift_to,
            'middleshift_from'  => $basisgdm->middleshift_from,
            'middleshift_to'    => $basisgdm->middleshift_to,
            'nightshift_from'   => $basisgdm->nightshift_from,
            'nightshift_to'     => $basisgdm->nightshift_to,
            'affectlearning'    => $basisgdm->affectlearning,
            'sport'             => $basisgdm->sport,
            'sporta1'           => $basisgdm->sporta1,
            'sporta2'           => $basisgdm->sporta2,
            'sportb1'           => $basisgdm->sportb1,
            'sportb2'           => $basisgdm->sportb2,
            'sportc1'           => $basisgdm->sportc1,
            'sportc2'           => $basisgdm->sportc2,
            'sportsum'          => $basisgdm->sportsum,
            'glucometer'        => $basisgdm->glucometer,
            'glucometerfrequency' => $basisgdm->glucometerfrequency,
            'g6pd'              => $basisgdm->g6pd,
            'g6pd_year'         => $basisgdm->g6pd_year,
            'g6pd_month'        => $basisgdm->g6pd_month,
            'closed'            => $basisgdm->closed,
            'close_date'        => $basisgdm->close_date,
            'closed_year'       => $basisgdm->closed_year,
            'closed_month'      => $basisgdm->closed_month,
            'closed_do'         => $basisgdm->closed_do,
            'closedcause'       => $basisgdm->closedcause,
            'closedreason'      => $basisgdm->closedreason
        ];
    }

}
