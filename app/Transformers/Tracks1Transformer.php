<?php

namespace App\Transformers;

use App\Tracks;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class Tracks1Transformer extends TransformerAbstract
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
    public function transform(Tracks $tracks)
    {
        return [
            'id'                           => $tracks->id,
            'basis_id'                     => $tracks->basis_id,
            'hosp_id'                      => $tracks->hosp_id,
            'pid'                          => $tracks->pid,
            'educator_user_id'             => json_decode($tracks->educator_user_id),
            'educator_previous'            => json_decode($tracks->educator_previous),
            'start_date'                   => $tracks->start_date,
            'end_date'                     => $tracks->end_date,
            'track_date'                   => $tracks->track_date,
            'date_previous'                => $tracks->date_previous,
            'contact_period'               => $tracks->contact_period,
            'bloodsugar_source'            => $tracks->bloodsugar_source,
            'contact_tel'                  => $tracks->contact_tel,
            'contact_mobile'               => $tracks->contact_mobile,
            'track1_contact'               => $tracks->track1_contact,
            'track1_tel'                   => $tracks->track1_tel,
            'track1_mobile'                => $tracks->track1_mobile,
            'track2_contact'               => $tracks->track2_contact,
            'track2_tel'                   => $tracks->track2_tel,
            'track2_mobile'                => $tracks->track2_mobile,
            'track3_contact'               => $tracks->track3_contact,
            'track3_tel'                   => $tracks->track3_tel,
            'track3_mobile'                => $tracks->track3_mobile,
            'track_reason'                 => $tracks->track_reason,
            'dietplan'                     => json_decode($tracks->dietplan),
            'dietplan_meal'                => $tracks->dietplan_meal,
            'dietplan_dessert'             => $tracks->dietplan_dessert,
            'dietplan_sugaramount'         => $tracks->dietplan_sugaramount,
            'monitor_mode'                 => $tracks->monitor_mode,
            'monitor_other'                => $tracks->monitor_other,
            'bloodsugar_from_beforemeal'   => $tracks->bloodsugar_from_beforemeal,
            'bloodsugar_to_beforemeal'     => $tracks->bloodsugar_to_beforemeal,
            'bloodsugar_from_aftermeal'    => $tracks->bloodsugar_from_aftermeal,
            'bloodsugar_to_aftermeal'      => $tracks->bloodsugar_to_aftermeal,
            'bloodsugar_from_beforesleep'  => $tracks->bloodsugar_from_beforesleep,
            'bloodsugar_to_beforesleep'    => $tracks->bloodsugar_to_beforesleep,
            'adjustprinciple_unit'         => $tracks->adjustprinciple_unit,
            'adjustprinciple_isf'          => $tracks->adjustprinciple_isf,
            'adjustprinciple_u'            => $tracks->adjustprinciple_u,
            'adjustprinciple_ci_morning'   => $tracks->adjustprinciple_ci_morning,
            'adjustprinciple_ci_afternoon' => $tracks->adjustprinciple_ci_afternoon,
            'adjustprinciple_ci_evening'   => $tracks->adjustprinciple_ci_evening,
            'medication'                   => $tracks->medication,
            'trace_status'                 => $tracks->trace_status,
            'status_previous'              => $tracks->status_previous,
            'remarks'                      => $tracks->remarks,
        ];
    }

}
