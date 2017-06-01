use marmot_test;

CREATE TABLE `pcore_system_test_a` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统测试用表A';

CREATE TABLE `pcore_system_test_b` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统测试用表B';

CREATE TABLE `pcore_system_test_tag` (
  `id` int(10) NOT NULL,
  `title_extra` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pcore_system_test_tag`
  ADD KEY `id` (`id`);

ALTER TABLE `pcore_system_test_a`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `pcore_system_test_b`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `pcore_system_test_a`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pcore_system_test_b`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `pcore_event_store` (
          `event_id` int(10) NOT NULL COMMENT '事件主键id',
          `source` varchar(255) NOT NULL COMMENT '来源对象',
          `source_id` int(10) NOT NULL COMMENT '来源id',
          `event_name` varchar(255) NOT NULL COMMENT '事件名称',
          `create_time` int(10) NOT NULL COMMENT '事件发生时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='事件仓库';

ALTER TABLE `pcore_event_store`
  ADD PRIMARY KEY (`event_id`);

ALTER TABLE `pcore_event_store`
  MODIFY `event_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '事件主键id', AUTO_INCREMENT=1;
