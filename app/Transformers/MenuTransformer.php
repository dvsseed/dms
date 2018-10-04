<?php

namespace App\Transformers;

use App\Menu;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class MenuTransformer extends TransformerAbstract
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
    public function transform(Menu $menu)
    {
        return [
            'id'         => $menu->id,
            'content'    => $menu->content
        ];
    }

}
