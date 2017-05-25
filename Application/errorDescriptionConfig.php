<?php

return array(
    USER_OLD_PASSWORD_NOT_CORRECT=>
        array(
            'id'=>USER_OLD_PASSWORD_NOT_CORRECT,
            'link'=>'',
            'status'=>403,
            'code'=> USER_OLD_PASSWORD_NOT_CORRECT,
            'title'=>'user old password not correct',
            'detail'=>'user old password not correct',
            'source'=>array(
                
            ),
            'meta'=>array()
        ),
    USER_IDENTIFY_DUPLICATE=>
        array(
            'id'=>USER_IDENTIFY_DUPLICATE,
            'link'=>'',
            'status'=>409,
            'code'=> USER_IDENTIFY_DUPLICATE,
            'title'=>'user identify duplicate',
            'detail'=>'user cellphone duplicate',
            'source'=>array(
                'pointer'=>'/data/attributes/cellPhone'
            ),
            'meta'=>array()
        ),
);
