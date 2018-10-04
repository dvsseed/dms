<?php

namespace App\Transformers;

use App\UserMenu;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class UserMenuTransformer extends TransformerAbstract
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
    public function transform(UserMenu $usermenu)
    {
        return [
            'id'         => $usermenu->id,
//            'name'       => $usermenu->name,
            'menu1'      => $usermenu->menu1,
            'menu2'      => $usermenu->menu2,
            'menu3'      => $usermenu->menu3,
            'menu4'      => $usermenu->menu4,
            'menu5'      => $usermenu->menu5,
            'menu6'      => $usermenu->menu6,
            'menu7'      => $usermenu->menu7,
            'menu8'      => $usermenu->menu8
        ];
    }

}
