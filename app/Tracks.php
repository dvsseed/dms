<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tracks extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'tracks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'basis_id', 'hosp_id', 'pid', 'educator_user_id', 'educator_previous', 'start_date', 'end_date', 'track_date', 'date_previous', 'contact_period', 'bloodsugar_source', 'contact_tel',
        'contact_mobile', 'track1_contact', 'track1_tel', 'track1_mobile', 'track2_contact', 'track2_tel', 'track2_mobile', 'track3_contact', 'track3_tel',
        'track3_mobile', 'track_reason', 'dietplan', 'dietplan_meal', 'dietplan_dessert', 'dietplan_sugaramount', 'monitor_mode', 'monitor_other',
        'bloodsugar_from_beforemeal', 'bloodsugar_to_beforemeal', 'bloodsugar_from_aftermeal', 'bloodsugar_to_aftermeal', 'bloodsugar_from_beforesleep',
        'bloodsugar_to_beforesleep', 'adjustprinciple_unit', 'adjustprinciple_isf', 'adjustprinciple_u', 'adjustprinciple_ci_morning',
        'adjustprinciple_ci_afternoon', 'adjustprinciple_ci_evening', 'medication', 'trace_status', 'status_previous', 'remarks'
    ];

}
