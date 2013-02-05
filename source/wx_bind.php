<?php

if ($_GET["op"]=="add"){

	include_once S_ROOT.'./uc_client/client.php';

	$username = empty($_POST['username']) ? '' : trim($_POST['username']);
	$password = empty($_POST['password']) ? '' : trim($_POST['password']);

	if(empty($username) || empty($password)) {
		showmessage('users_were_not_empty_please_re_login',  'wx.php?do=bind&wxkey='.$_GET['wxkey']);
	}

	// 登陆验证
	if(!$passport = getpassport($username, $password)) {
		
		showmessage('login_failure_please_re_login',  'wx.php?do=bind&wxkey='.$_GET['wxkey']);
	}


	updatetable('space', array('wxkey'=>$_POST['wxkey']), array('uid'=>$passport['uid']));

	showmessage('do_success', 'www.baid.com', 0);

}

include_once template("./wx/template/bind");

?>