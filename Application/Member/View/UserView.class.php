<?php
namespace Member\View;

use Member\Model\User;
use Common\View\JsonApiView;
use Marmot\Framework\Interfaces\IView;

/**
 * @codeCoverageIgnore
 */
class UserView implements IView
{
    use JsonApiView;

    private $rules;

    private $data;
    
    private $encodingParameters;

    public function __construct($data, $encodingParameters = null)
    {
        $this->data = $data;
        $this->encodingParameters = $encodingParameters;

        $this->rules = array(
            User::class => UserSchema::class
        );
    }

    public function display()
    {
        return $this->jsonApiFormat($this->data, $this->rules, $this->encodingParameters);
    }
}
