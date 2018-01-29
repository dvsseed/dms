<?php

namespace App\Transformers;

use App\Log;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class LogTransformer extends TransformerAbstract
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
    public function transform(Log $log)
    {
        return [
            'id'         => $log->id,
            'table'      => $log->table,
            'action'	 => $log->action,
            'user_id'	 => $log->user_id,
            'ip'         => $log->ip,
            'useragent'  => $log->useragent,
            'updated_at' => $log->updated_at
        ];
    }

}
