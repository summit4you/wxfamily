<?php

/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: do_register.php 13111 2009-08-12 02:39:58Z liguode $
*/
include_once(S_ROOT.'./source/function_cp.php');
include_once(S_ROOT.'./source/function_magic.php');

$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('space')." WHERE wxkey='$_GET[wxkey]'");
$space=$_SGLOBAL['db']->fetch_array($query);

if ($_GET[op]=="mobileinvite"){
	


	$siteurl = getsiteurl();

	$friendnum = getcount('friend', array('fuid'=>$space['uid'], 'status'=>0));

	$maxcount = 50;//最多好友邀请
	$reward = getreward('invitecode', 0);
	$appid = empty($_GET['app']) ? 0 : intval($_GET['app']);

	$inviteapp = $invite_code = '';
	if(empty($reward['credit']) || $appid) {
		$reward['credit'] = 0;
		$invite_code = space_key($space, $appid);
	}

	$siteurl = getsiteurl();
	$spaceurl = $siteurl.'space.php?uid='.$_SGLOBAL['supe_uid'];
	$mailvar = array(
		"<a href=\"$spaceurl\">".avatar($space['uid'], 'middle')."</a><br>".$_SN[$space['uid']],
		$_SN[$space['uid']],
		$_SCONFIG['sitename'],
		'',
		'',
		$spaceurl,
		''
	);

	//取出相应的应用
	$appinfo = array();
	if($appid) {
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('myapp')." WHERE appid='$appid'");
		$appinfo = $_SGLOBAL['db']->fetch_array($query);
		if($appinfo) {
			$inviteapp = "&amp;app=$appid";
			$mailvar[6] = $appinfo['appname'];
		} else {
			$appid = 0;
		}
	}

	

	if (!empty($space)){

		$username = trim($_POST['username']);
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
				'uid' => $space['uid'],
				'code' => $_POST['password'],
				'email' => saddslashes($value),
				'type' => 1
			);
			$id = inserttable('invite', $setarr, 1);
			realname_set($setarr['uid'],$space['username']);
			if ($id){
				
				
				$space2 = addmember($username, $_POST['password'], $username.'@familyday.com.cn');
			
				invite_update($id, $space2['uid'], $space2['username'], $space['uid'], $space['username'], 0);
				
				
				realname_get();
				SendMessage($username,smlang('invite_friend',array($name,$_POST['password'],$_SN[$setarr['uid']])));
				
				if($reward['credit']) {
					$credit = intval($reward['credit']);
					$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET credit=credit-$credit WHERE uid='$space[uid]'");
				}
				
			}else{
			   
			}
			
		} else {
			

			$setarr = array(
				'uid' => $space['uid'],
				'code' => $_POST['password'],
				'email' => saddslashes($value),
				'type' => 1
			);
			$id = inserttable('invite', $setarr, 1);
			 realname_set($setarr['uid'],$space['username']);
			$space2 = addmember($username, $_POST['password'], $username.'@familyday.com.cn');
			
			invite_update($id, $space2['uid'], $space2['username'], $space['uid'], $space['username'], 0);
			
			realname_get();
			 SendMessage($username,smlang('invite_friend',array($name,$_POST['password'],$_SN[$setarr['uid']])));
			if($reward['credit']) {
					$credit = intval($reward['credit']);
					$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET credit=credit-$credit WHERE uid='$space[uid]'");
			}
			
		}

	   wxshowmessage('邀请成功', 'wx.php?do=feed&wxkey='.$_GET['wxkey']);
	}else{
		wxshowmessage('login_failure_please_re_login',  'wx.php?do=bind&wxkey='.$_GET['wxkey']);
	}
}


realname_set($space['uid'],$space['username']);

realname_get();
$result=0;

$code = strtolower(random(6));

include_once template("./wx/template/invite");
?>