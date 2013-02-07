<?php

if (isset($_COOKIE['uchome_m_auth'])) 
{
 	$m_auth=$_COOKIE['uchome_m_auth'];
 	$wxkey=$_GET['wxkey'];
}else{
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('space')." WHERE wxkey='$_GET[wxkey]'");

	if ($value=$_SGLOBAL['db']->fetch_array($query)){
		updatetable('space', array('wxkey'=>''), array('uid'=>$value['uid']));
	}
	showmessage('login_failure_please_re_login',  'wx.php?do=bind&wxkey='.$_GET['wxkey']);
}

if ($_GET[op]=="photo"){
	
	$taglist = array();

	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('space')." WHERE wxkey='$_GET[wxkey]'");

	if ($space=$_SGLOBAL['db']->fetch_array($query)){
		$query = $_SGLOBAL['db']->query("select * from ".tname('tag')." where close = 0 and uid='$space[uid]'");
		while($value = $_SGLOBAL['db']->fetch_array($query)){
			$taglist[] = $value;
		}
	}
}


include_once template("./wx/template/cp");
?>