<?php

if ($_GET["op"]=="add"){

	include_once S_ROOT.'./uc_client/client.php';

	$username = empty($_REQUEST['username']) ? '' : trim($_REQUEST['username']);
	$password = empty($_REQUEST['password']) ? '' : trim($_REQUEST['password']);

	if(empty($username) || empty($password)) {
		showmessage('users_were_not_empty_please_re_login', 'wx.php?do=bind&wxkey='.$_POST['wxkey']);
	}

	// 登陆验证
	if(!$passport = getpassport($username, $password)) {
		
		showmessage('login_failure_please_re_login', 'wx.php?do=bind&wxkey='.$_POST['wxkey']);
	}


	updatetable('space', array('wxkey'=>$_POST['wxkey']), array('uid'=>$passport['uid']));
	showmessage('do_success', 'wx.php?do=feed', 0);

}

include_once template("./wx/template/bind");

?>