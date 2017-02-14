use marmot;

CREATE TABLE `pcore_user` (
  `user_id` int(10) NOT NULL COMMENT '用户主键id',
  `cellphone` char(11) DEFAULT NULL COMMENT '用户手机号',
  `password` char(32) NOT NULL COMMENT '用户密码',
  `salt` char(4) NOT NULL COMMENT '盐杂质',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NOT NULL COMMENT '更新时间',
  `status` tinyint(1) NOT NULL COMMENT '状态(STATUS_NORMAL,0,默认),(STATUS_DELETE,0,删除)',
  `status_time` int(10) NOT NULL COMMENT '状态更新时间',
  `real_name` varchar(255) NOT NULL COMMENT '真实姓名',
  `user_name` varchar(255) NOT NULL COMMENT '用户名',
  `nick_name` varchar(255) NOT NULL COMMENT '昵称'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='买家用户表';

ALTER TABLE `pcore_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `cellphone` (`cellphone`);

ALTER TABLE `pcore_user`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户主键id', AUTO_INCREMENT=1;