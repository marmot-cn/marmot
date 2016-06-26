<?php
namespace User\View;
// use Tobscure\JsonApi\AbstractSerializer;
// use Tobscure\JsonApi\Relationship;
// use Tobscure\JsonApi\Collection;

use \Neomerx\JsonApi\Schema\SchemaProvider;

class UserSerializer extends SchemaProvider{

    protected $resourceType = 'users';

    public function getAttributes($user){

        return [
            'cellPhone'  => $user->getCellPhone(),
            'nickName'  => $user->getNickName(),
        ];
    }

    public function getId($user){
    	
	    return $user->getId();
	}

    public function getRelationships($user, $isPrimary, array $includeList){

        return [
            'banks' => [self::DATA => $user->bank,
                         self::SHOW_SELF    => true,
                          self::SHOW_RELATED => true
                        ],
        ];
    }

    public function getIncludePaths(){
        return [
            'banks',
        ];
    }

    // public function bank($user){
    //     $element = new Collection($user->bank, new BankSerializer);

    //     $relationship = new Relationship($element);
    //     $relationship->addLink('self','/users/'.$user->getId().'/relationships/banks');
    //     $relationship->addLink('related','/users/'.$user->getId().'/banks');

    //     return $relationship;
    // }

    // public function getLinks($user) {
    //     return ['self' => '/users/' . $user->getId()];
    // }
}