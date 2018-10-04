<?php

namespace App\Transformers;

use App\Checkhistory;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class CheckhistoryTransformer extends TransformerAbstract
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
    public function transform(Checkhistory $checkhistory)
    {
        return [
            'id'                 => $checkhistory->id,
            'hosp_id'            => $checkhistory->hosp_id,
            'check_date'         => $checkhistory->check_date,
            'name'               => $checkhistory->name,
            'pid'                => $checkhistory->pid,
            'birthday'           => $checkhistory->birthday,
            'sex'                => $checkhistory->sex,
            'items'              => $checkhistory->items,
            'values'             => $checkhistory->values,
            'serialno'           => $checkhistory->serialno,
            'print_date'         => $checkhistory->print_date,
        ];
    }

}
