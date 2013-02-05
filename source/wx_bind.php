<?php

if ($_GET["op"]=="add"){

	include_once S_ROOT.'./uc_client/client.php';

	$username = empty($_POST['username']) ? '' : trim($_POST['username']);
	$password = empty($_POST['password']) ? '' : trim($_POST['password']);

	if(empty($username) || empty($password)) {
		capi_showmessage_by_data('users_were_not_empty_please_re_login', 1);
	}
	
	// 登陆验证
	if(!$passport = getpassport($username, $password)) {
		
		capi_showmessage_by_data('login_failure_please_re_login', 1);
	}


	updatetable('space', array('wxkey'=>$_POST['wxkey']), array('uid'=>$passport['uid']));

	capi_showmessage_by_data('do_success', 0);

}

include_once template("./wx/template/bind");

?>