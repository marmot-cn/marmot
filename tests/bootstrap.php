<?php

require './Core.php';
require S_ROOT.'/tests/GenericTestsDataBaseTestCase.php';

error_reporting(6143);

$core = Core::getInstance();
$core -> initTest();