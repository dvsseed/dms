<?php

namespace App\Transformers;

use App\Basis;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class BasisTransformer extends TransformerAbstract
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
            'id'           => $basis->id,
            'hosp_id'      => $basis->hosp_id,
            'medical_number' => $basis->medical_number,
            'name'         => $basis->name,
            'birthday'     => $basis->birthday,
            'pid'          => $basis->pid,
            'sex'          => $basis->sex,
            'tel1'         => $basis->tel1,
            'tel2'         => $basis->tel2,
            'company_tel'  => $basis->company_tel,
            'mobile1'      => $basis->mobile1,
            'mobile2'      => $basis->mobile2,
            'inform_addr'  => $basis->inform_addr,
            'emergency_contact' => $basis->emergency_contact,
            'emergency_relationship' => $basis->emergency_relationship,
            'emergency_relationship_other' => $basis->emergency_relationship_other,
            'emergency_tel' => $basis->emergency_tel,
            'emergency_mobile' => $basis->emergency_mobile,
            'language' => $basis->language,
            'language_other' => $basis->language_other,
            'drug_allergy' => $basis->drug_allergy,
            'drug_allergy_insulin' => $basis->drug_allergy_insulin,
            'drug_allergy_other' => $basis->drug_allergy_other
        ];
    }

}
