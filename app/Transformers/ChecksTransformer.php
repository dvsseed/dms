<?php

namespace App\Transformers;

use App\Checks;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class ChecksTransformer extends TransformerAbstract
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
    public function transform(Checks $checks)
    {
        return [
            'id'                 => $checks->id,
            'hosp_id'            => $checks->hosp_id,
            'check_date'         => $checks->check_date,
            'name'               => $checks->name,
            'pid'                => $checks->pid,
            'birthday'           => $checks->birthday,
            'sex'                => $checks->sex,
            'items'              => $checks->items,
            'values'             => $checks->values,
            'serialno'           => $checks->serialno,
            'print_date'         => $checks->print_date,
        ];
    }

}
