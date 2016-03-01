<?php
/**
 * 通用消息类
 * 
 * @author chloroplast
 * @version 1.0.20160223
 */
class Message{

	
	/**
	 * @var string $title 信息标题
	 */
	private $title;

	/**
	 * @var string $content 信息内容
	 */
	private $content;

	/**
	 * @var string $targets 信息目标
	 */
	private $targets;


	public function setTitle($title){

	}

    /**
     * Gets the value of title.
     *
     * @return string $title 信息标题
     */
    public function getTitle(){
        return $this->title;
    }

    public function setContent(){

    }

    /**
     * Gets the value of content.
     *
     * @return string $content 信息内容
     */
    public function getContent(){
        return $this->content;
    }

    public function setTargets(){

    }

    /**
     * Gets the value of targets.
     *
     * @return string $targets 信息目标
     */
    public function getTargets(){
        return $this->targets;
    }
}