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
