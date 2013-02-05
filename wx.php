<?php

include_once('./../common.php');

//ÔÊÐí¶¯×÷
$dos = array('bind', 'feed', 'cp');
$do = (!empty($_GET['do']) && in_array($_GET['do'], $dos))?$_GET['do']:'bind';

include_once(S_ROOT."./wx/wx_{$do}.php");

?>