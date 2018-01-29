<?php

namespace App\Transformers;

use App\Basis;
use App\Cases;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class Cases2Transformer extends TransformerAbstract
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
    public function transform(Basis $basis)
    {
        return [
            'case_date'          => $basis->case_date,
            'name'               => $basis->name,
            'pid'                => $basis->pid,
            'cure_stage'         => $basis->cure_stage
        ];
    }

    public function includeCases(Basis $basis)
    {
        return $this->collection($basis->cases, new BasisTransformer);
    }
}
