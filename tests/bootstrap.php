<?php

require './Core.php';
require S_ROOT.'/tests/GenericTestsDataBaseTestCase.php';
require S_ROOT.'/tests/GenericTestCase.php';
require S_ROOT.'/tests/MyAppDbUnitArrayDataSet.php';

$core = \Marmot\Core::getInstance();
$core -> initTest();
