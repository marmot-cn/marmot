use marmot;


-- --------------------------------------------------------

ALTER TABLE `pcore_user` ADD `other_info` JSON NULL COMMENT '用于测试json格式' AFTER `nick_name`;

-- --------------------------------------------------------