<?php

namespace App\Transformers;

use App\BasisCache;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class BasisCacheTransformer extends TransformerAbstract
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
    public function transform(BasisCache $basiscache)
    {
        return [
            'id'           => $basiscache->id,
            'user_id'      => $basiscache->user_id,
            'pid'          => $basiscache->pid,
            'birthday'     => $basiscache->birthday
        ];
    }

}
