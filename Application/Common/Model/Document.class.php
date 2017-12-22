<?php
namespace Common\Model;

abstract class Document
{
    /**
     * @var string $id 存储 Mongo id,char 24
     */
    private $id;
    /**
     * @var array $data 存储信息
     */
    private $data;

    public function __construct(
        string $id = '',
        array $data = array()
    ) {
        $this->id = !empty($id) ? $id : '';
        $this->data = !empty($data) ? $data : array();
    }

    public function __destruct()
    {
        unset($this->id);
        unset($this->data);
    }

    /**
     * 设置存储 mongo id
     * @param string $id 存储 mongo id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * 获取存储 mongo id
     * @return string $id 存储 mongo id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 设置存储信息
     * @param array $data 存储信息
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * 获取存储信息
     * @return array $data 存储信息
     */
    public function getData() : array
    {
        return $this->data;
    }
}
