<?php

include_once('./../common.php');
include_once( 'botutil.php' );

//ÔÊÐí¶¯×÷
$dos = array('bind', 'feed', 'cp', 'detail');
$do = (!empty($_GET['do']) && in_array($_GET['do'], $dos))?$_GET['do']:'bind';

include_once(S_ROOT."./wx/source/wx_{$do}.php");

?>