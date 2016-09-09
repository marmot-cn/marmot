<?php
namespace System\View;

use System\Interfaces\IView;

class EmptyView implements IView
{
   
    private $rules;

    private $data;

    public function __construct()
    {
        //判断data是否合法
        //单个是否为对象,多个是否为数组的对象
        $this->data = '';

        $this->rules = array();
    }

    public function display()
    {
        return '';
    }
}
