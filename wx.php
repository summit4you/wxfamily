<?php

include_once('./../common.php');
include_once( 'botutil.php' );
include_once( 'wx_common.php' );

define(BLOCK_TAG_START, "{{");
define(BLOCK_TAG_END, "}}");
define(DOLLOR_TAG, "$");
//ÔÊÐí¶¯×÷
$dos = array('bind', 'feed', 'cp', 'detail', 'reg', 'setting', 'invite');
$do = (!empty($_GET['do']) && in_array($_GET['do'], $dos))?$_GET['do']:'bind';

include_once(S_ROOT."./wx/source/wx_{$do}.php");

?>