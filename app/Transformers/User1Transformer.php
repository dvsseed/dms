<?php

namespace App\Transformers;

use App\User;
use Hashids;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class User1Transformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var  array
     */
    protected $availableIncludes = ['role'];

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
    public function transform(User $user)
    {
        return [
            'id'          => $user->id,
            'username'     => $user->username
        ];
    }

}
