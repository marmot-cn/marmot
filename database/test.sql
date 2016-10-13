CREATE DATABASE marmot_test DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

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

ALTER TABLE `pcore_system_test_a`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `pcore_system_test_b`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `pcore_system_test_a`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pcore_system_test_b`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
