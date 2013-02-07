<?php

/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: do_register.php 13111 2009-08-12 02:39:58Z liguode $
*/
include_once(S_ROOT.'./source/function_cp.php');
include_once(S_ROOT.'./source/function_magic.php');

if ($_GET[op]=="mobileinvite"){

	$username = trim($_POST['phonenum']);
	$name = trim($_POST['name']);
	if(empty($username)) {
		wxshowmessage('user_name_is_not_legitimate');
	}elseif(!preg_match("/^0?(13[0-9]|15[012356789]|18[0236789]|14[57])[0-9]{8}$/",$username)){
	    wxshowmessage('user_name_is_not_legitimate');
	}

   if($reward['credit']) {
			//计算积分扣减积分
		$credit = intval($reward['credit'])*($invitenum+1);
		
		$setarr = array(
			'uid' => $_SGLOBAL['supe_uid'],
			'code' => $_POST['password'],
			'email' => saddslashes($value),
			'type' => 1
		);
		$id = inserttable('invite', $setarr, 1);
		realname_set($setarr['uid'],$_SGLOBAL['supe_username']);
		if ($id){
			
			
			$space2 = addmember($username, $_POST['password'], $username.'@aifaxian.com');
		
			invite_update($id, $space2['uid'], $space2['username'], $space['uid'], $space['username'], 0);
			
			
			realname_get();
			SendMessage($username,smlang('invite_friend',array($name,$_POST['password'],$_SN[$setarr['uid']])));
			
			if($reward['credit']) {
				$credit = intval($reward['credit']);
				$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET credit=credit-$credit WHERE uid='$_SGLOBAL[supe_uid]'");
			}
			
		}else{
		   
		}
		
	} else {
		

		$setarr = array(
			'uid' => $_SGLOBAL['supe_uid'],
			'code' => $_POST['password'],
			'email' => saddslashes($value),
			'type' => 1
		);
		$id = inserttable('invite', $setarr, 1);
         realname_set($setarr['uid'],$_SGLOBAL['supe_username']);
		$space2 = addmember($username, $_POST['password'], $username.'@aifaxian.com');
		
		invite_update($id, $space2['uid'], $space2['username'], $space['uid'], $space['username'], 0);
		
		realname_get();
		 SendMessage($username,smlang('invite_friend',array($name,$_POST['password'],$_SN[$setarr['uid']])));
		if($reward['credit']) {
				$credit = intval($reward['credit']);
				$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET credit=credit-$credit WHERE uid='$_SGLOBAL[supe_uid]'");
		}
		
	}

   wxshowmessage('邀请成功', 'wx.php?do=feed&wxkey='.$_GET['wxkey']);
}





$result=0;


include_once template("./wx/template/invite");
?>