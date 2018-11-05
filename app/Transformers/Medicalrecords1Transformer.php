<?php

namespace App\Transformers;

use App\Medicalrecords;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class Medicalrecords1Transformer extends TransformerAbstract
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
    public function transform(Medicalrecords $mrecords)
    {
        return [
            'id'                           => $mrecords->id,
            'name'                         => $mrecords->name,
            'pid'                          => $mrecords->pid,
            'mrdate'                       => $mrecords->mrdate,
            'start_date'                   => $mrecords->start_date,
            'soap_date'                    => $mrecords->soap_date,
            'ename'                        => json_decode($mrecords->ename),
            'sname'                        => json_decode($mrecords->sname)
        ];
    }

}
