<?php

if (isset($_COOKIE['uchome_m_auth'])) 
{
 	$m_auth=$_COOKIE['uchome_m_auth'];
 	$wxkey=$_COOKIE['uchome_wxkey'];
}else{
	showmessage('login_failure_please_re_login',  'wx.php?do=bind&wxkey='.$_GET['wxkey']);
}

include_once template("./wx/template/detail");
?>