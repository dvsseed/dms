<?php

namespace App\Transformers;

use App\Doctor;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class Doctor1Transformer extends TransformerAbstract
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
    public function transform(Doctor $doctor)
    {
        return [
            'label'          => $doctor->name,
            'value'          => $doctor->pid
        ];
    }

}
