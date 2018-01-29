<?php

namespace App\Transformers;

use App\Mresult;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class MresultTransformer extends TransformerAbstract
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
    public function transform(Mresult $mresult)
    {
        return [
            'id'           => $mresult->id,
            'basis_id'     => $mresult->basis_id,
            'hosp_id'      => $mresult->hosp_id,
            'pid'          => $mresult->pid,
            'mr_date'      => $mresult->mr_date,
            'mr_time'      => json_decode($mresult->mr_time),
            'slot'         => $mresult->slot,
            'slotname'     => $mresult->slotname,
            'value1'       => $mresult->value1,
            'value2'       => $mresult->value2,
            'value3'       => $mresult->value3,
            'note'         => $mresult->note,
            'autonote'     => $mresult->autonote,
            'mdataid'      => $mresult->mdataid,
            'modifyid'     => $mresult->modifyid,
            'diaryid'      => $mresult->diaryid
        ];
    }

}
