<?php

interface TemplateInterface {

	/**
	 * 加载配置文件
	 * @var string $profile 配置文件路径
	 */
	public function loadProfile($profile);
	/**
	 * 生成文件
	 */
	public function generate();
}