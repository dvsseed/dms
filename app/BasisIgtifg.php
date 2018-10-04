<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BasisIgtifg extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'basis_igtifg';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'basis_id', 'pid', 'log_date', 'fall_ill_year', 'fall_ill_month', 'fall_ill_ii',
        'comorbidity', 'lung', 'liver', 'mental', 'cancer', 'gestation', 'stopgestation',
        'fami_medic_his', 'relatives', 'hypertension', 'cardiovascular', 'stroke', 'activity', 'education', 'occupation', 'worktime', 'fixedtime_from', 'fixedtime_to',
        'dayshift_from', 'dayshift_to', 'middleshift_from', 'middleshift_to', 'nightshift_from', 'nightshift_to', 'affectlearning', 'livingcondition', 'nursinghome',
        'careunit', 'livingtel', 'selfcare', 'caregiver', 'caretel', 'sport', 'sporta1', 'sporta2', 'sportb1', 'sportb2', 'sportc1', 'sportc2', 'sportsum',
        'closed', 'close_date', 'closed_do', 'closed_year', 'closed_month', 'closedcause', 'closedreason'
    ];

}
