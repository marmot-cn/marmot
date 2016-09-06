<?php
namespace Home\View;

use Home\Model\{News, Comment};
use Common\View\JsonApiView;

use System\Interfaces\IView;

class NewsView implements IView
{	

	use JsonApiView;

	private $rules;

	private $data;

	private $links;

	public function __construct($data)
	{	
		//判断data是否合法
		//单个是否为对象,多个是否为数组的对象
		$this->data = $data;

		$this->rules = array(
			Comment::class => CommentSchema::class,
         	News::class => NewsSchema::class
        );
	}

	public function display()
	{	
		return $this->jsonApiFormat($this->data, $this->rules, '', $this->links);
	}
}