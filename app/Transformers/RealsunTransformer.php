<?php

namespace App\Transformers;

use App\Realsun;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class RealsunTransformer extends TransformerAbstract
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
    public function transform(Realsun $realsun)
    {
        return [
            'id'                 => $realsun->id,
            'hosp_id'            => $realsun->hosp_id,
            'drugcode'           => $realsun->drugcode,
            'check_date'         => $realsun->check_date,
            'checkno'            => $realsun->checkno,
            'name'               => $realsun->name,
            'birthday'           => $realsun->birthday,
            'pid'                => $realsun->pid,
            'tel'                => $realsun->tel,
            'diseasename'        => $realsun->diseasename,
            'prescriptiondose'   => $realsun->prescriptiondose,
            'expirydate'         => $realsun->expirydate,
        ];
    }

}
