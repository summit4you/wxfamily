<?php

$result = 0;

$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('space')." WHERE wxkey='$_GET[wxkey]'");

if ($_SGLOBAL['db']->fetch_array($query)){
	$result = 1;
}else{
	if (isset($_COOKIE['uchome_m_auth'])) 
	{
		$result = 1;
	}else{
		$result = 0;
	}
}

if ($_GET["op"]=="add"){

	include_once S_ROOT.'./uc_client/client.php';

	$username = empty($_POST['username']) ? '' : trim($_POST['username']);
	$password = empty($_POST['password']) ? '' : trim($_POST['password']);

	if(empty($username) || empty($password)) {
		// showmessage('users_were_not_empty_please_re_login',  'wx.php?do=bind&wxkey='.$_POST['wxkey']);
		$result = -1;
		include_once template("./wx/template/bind");
		exit;
	}

	// 登陆验证
	if(!$passport = getpassport($username, $password)) {
		
		// showmessage('login_failure_please_re_login',  'wx.php?do=bind&wxkey='.$_POST['wxkey']);
		$result = -1;
		include_once template("./wx/template/bind");
		exit;
	}


	updatetable('space', array('wxkey'=>$_POST['wxkey']), array('uid'=>$passport['uid']));

	// 同步登陆
	$jsonurl = "http://www.familyday.com.cn/dapi/do.php?ac=login&iscookie=1&username=".$_POST['username']."&password=".$_POST['password'];
	$json = file_get_contents($jsonurl,0,null,null);
	$json_output = json_decode($json);
	ssetcookie('m_auth',$json_output->data->m_auth, time()+3600*24*365);
	echo "<script>localStorage.setItem('auth','".$json_output->data->m_auth."');</script>";
	echo "<script>localStorage.setItem('wxkey','".$_POST['wxkey']."');</script>";

	ssetcookie('wxkey',$_POST['wxkey'], time()+3600*24*365);

	// showmessage('do_success', 'wx.php?do=feed&wxkey='.$_POST['wxkey'], 0);

	$result = 1;

}

include_once template("./wx/template/bind");

?>