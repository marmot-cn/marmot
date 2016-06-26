<?php
namespace User\View;
// use Tobscure\JsonApi\AbstractSerializer;

use \Neomerx\JsonApi\Schema\SchemaProvider;

class BankSerializer extends SchemaProvider{

    protected $resourceType = 'banks';

    public function getId($bank){
        
        return $bank->id;
    }

    public function getAttributes($bank){

        return [
            'name'  => $bank->name,
            'time'  => $bank->time,
        ];
    }

}