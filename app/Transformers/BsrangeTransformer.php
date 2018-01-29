<?php

namespace App\Transformers;

use App\Bsrange;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class BsrangeTransformer extends TransformerAbstract
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
    public function transform(Bsrange $bsrange)
    {
        return [
            'id'                => $bsrange->id,
            'basis_id'          => $bsrange->basis_id,
            'hosp_id'           => $bsrange->hosp_id,
            'pid'               => $bsrange->pid,
            'deepnightlow'      => $bsrange->deepnightlow,
            'deepnighthigh'     => $bsrange->deepnighthigh,
            'weekuplow'         => $bsrange->weekuplow,
            'weekuphigh'        => $bsrange->weekuphigh,
            'beforebreaklow'    => $bsrange->beforebreaklow,
            'beforebreakhigh'   => $bsrange->beforebreakhigh,
            'afterbreaklow'     => $bsrange->afterbreaklow,
            'afterbreakhigh'    => $bsrange->afterbreakhigh,
            'beforenoonlow'     => $bsrange->beforenoonlow,
            'beforenoonhigh'    => $bsrange->beforenoonhigh,
            'afternoonlow'      => $bsrange->afternoonlow,
            'afternoonhigh'     => $bsrange->afternoonhigh,
            'beforedinnerlow'   => $bsrange->beforedinnerlow,
            'beforedinnerhigh'  => $bsrange->beforedinnerhigh,
            'afterdinnerlow'    => $bsrange->afterdinnerlow,
            'afterdinnerhigh'   => $bsrange->afterdinnerhigh,
            'sleeplow'          => $bsrange->sleeplow,
            'sleephigh'         => $bsrange->sleephigh,
            'deepnightftime'    => $bsrange->deepnightftime,
            'deepnightttime'    => $bsrange->deepnightttime,
            'weekupftime'       => $bsrange->weekupftime,
            'weekupttime'       => $bsrange->weekupttime,
            'beforebreakftime'  => $bsrange->beforebreakftime,
            'beforebreakttime'  => $bsrange->beforebreakttime,
            'afterbreakftime'   => $bsrange->afterbreakftime,
            'afterbreakttime'   => $bsrange->afterbreakttime,
            'beforenoonftime'   => $bsrange->beforenoonftime,
            'beforenoonttime'   => $bsrange->beforenoonttime,
            'afternoonftime'    => $bsrange->afternoonftime,
            'afternoonttime'    => $bsrange->afternoonttime,
            'beforedinnerftime' => $bsrange->beforedinnerftime,
            'beforedinnerttime' => $bsrange->beforedinnerttime,
            'afterdinnerftime'  => $bsrange->afterdinnerftime,
            'afterdinnerttime'  => $bsrange->afterdinnerttime,
            'sleepftime'        => $bsrange->sleepftime,
            'sleepttime'        => $bsrange->sleepttime
        ];
    }

}
