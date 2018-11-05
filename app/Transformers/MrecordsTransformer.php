<?php

namespace App\Transformers;

use App\Mrecords;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class MrecordsTransformer extends TransformerAbstract
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
    public function transform(Mrecords $mrecords)
    {
        return [
            'id'                           => $mrecords->id,
            'basis_id'                     => $mrecords->basis_id,
            'hosp_id'                      => $mrecords->hosp_id,
            'pid'                          => $mrecords->pid,
            'educator_user_id'             => $mrecords->educator_user_id,
            'soap_date'                    => $mrecords->soap_date,
            'close_date'                   => $mrecords->close_date,
        ];
    }

}
