<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BasisGdm extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'basis_gdm';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'basis_id', 'pid', 'log_date', 'fall_ill_year', 'fall_ill_month', 'currentweeks', 'maternitynumber', 'gestationaldiabetes', 'symptom', 'treatment', 'medication', 'medication_year', 'medication_month',
        'injection_year', 'injection_month', 'insulin', 'longterm', 'ineffect', 'shortacting', 'quickeffect', 'glp1_year', 'glp1_month', 'glp1',
        'comorbidity', 'lung', 'liver', 'mental', 'cancer', 'gestation', 'stopgestation',
        'fami_medic_his', 'relatives', 'hypertension', 'cardiovascular', 'stroke', 'activity', 'education', 'occupation', 'worktime', 'fixedtime_from', 'fixedtime_to',
        'dayshift_from', 'dayshift_to', 'middleshift_from', 'middleshift_to', 'nightshift_from', 'nightshift_to', 'affectlearning',
        'sport', 'sporta1', 'sporta2', 'sportb1', 'sportb2', 'sportc1', 'sportc2', 'sportsum',
        'glucometer', 'glucometerfrequency', 'g6pd', 'g6pd_year', 'g6pd_month', 'closed', 'close_date', 'closed_year', 'closed_month', 'closed_do', 'closedcause', 'closedreason'
    ];

}
