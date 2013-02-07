<?php
/**
  * wechat php test
  */

include_once('./../common.php');
include_once( 'botutil.php' );


//define your token
define("TOKEN", "family");
$wechatObj = new wechatCallbackapiTest();
//$wechatObj->valid();
$wechatObj->responseMsg();


class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

	public function welcome($toUsername) {
        if($toUsername=="gh_71e78c3b0890"){
            return      "你好！欢迎来到“我家”，绑定微信机器人，帮你快速了解家人动态，还可以快速发布照片和文字，立即跟家人分享！

•回复【1】——查看家人动态；
•回复【2】——发表照片和日记；
•回复【3】——把微信绑定到我家；
•回复【4】——注册我家账号；
";
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		
      	//extract post data
		if (!empty($postStr)){
                
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
				
				if(!empty( $keyword ))
                {
					runlog("wx", $keyword);
					if ($keyword=='Hello2BizUser'){
						$msgType = "text";
						$contentStr = $this->welcome($toUsername);
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}elseif($keyword == "1"){
						$msgType = "news";
						
						$jsonurl = "http://www.familyday.com.cn/dapi/space.php?do=wxfeed&perpage=5&page=1&wxkey=".$fromUsername;
						$json = file_get_contents($jsonurl,0,null,null);
						$json_output = json_decode($json);

						if ($json_output->data->error==0){
							$articles = array();
							foreach ($json_output->data as $key => $obj)
							{
								$obj->message = html_entity_decode($obj->message);
								$obj->message = html_entity_decode($obj->message);
								$obj->message = strip_tags($obj->message);
								$msg = $obj->username.":".$obj->title."\n".$obj->message;

								if ($obj->image_1)
								{
									$pic = $obj->image_1;
								}else{
									$pic = "http://www.familyday.com.cn/wx/image/nopic.gif";
								}
								$url = "http://www.familyday.com.cn/wx/wx.php?do=detail&id=".$obj->id."&uid=".$obj->uid."&idtype=".$obj->idtype."&wxkey=".$fromUsername;
								$articles[] = makeArticleItem($msg, $msg, $pic, $url);
							}
							$url = "http://www.familyday.com.cn/wx/wx.php?do=feed&wxkey=".$fromUsername;
							$pic = "http://www.familyday.com.cn/wx/images/feed-icon.jpg";
							$articles[] = makeArticleItem("更多动态", "更多动态", $pic, $url);
							$resultStr = makeArticles($fromUsername, $toUsername, $time, $msgType, "家庭动态",$articles); 
			
						}else{
							$url = "http://www.familyday.com.cn/wx/wx.php?do=bind&wxkey=".$fromUsername;
							$pic = "http://www.familyday.com.cn/wx/images/bind.jpg";
							$articles[] = makeArticleItem("绑定微信帐号", "你还没有绑定微信号，请点击进入微信绑定页", $pic, $url);
							$resultStr = makeArticles($fromUsername, $toUsername, $time, $msgType, "绑定微信帐号",$articles); 
						}
						

					}elseif($keyword == "2"){

						$msgType = "news";
					
						$jsonurl = "http://www.familyday.com.cn/dapi/space.php?do=wxfeed&perpage=5&page=1&wxkey=".$fromUsername;;
						$json = file_get_contents($jsonurl,0,null,null);
						$json_output = json_decode($json);

						if ($json_output->data->error==0){
							$url = "http://www.familyday.com.cn/wx/wx.php?do=cp&op=photo&wxkey=".$fromUsername;
							$pic = "http://www.familyday.com.cn/wx/images/image-icon.jpg";
							$articles[] = makeArticleItem("发布图片，分享给家人", "发布图片，分享给家人", $pic, $url);
							
							$url = "http://www.familyday.com.cn/wx/wx.php?do=cp&op=blog&wxkey=".$fromUsername;
							$pic = "http://www.familyday.com.cn/wx/images/blog-icon.jpg";
							$articles[] = makeArticleItem("发一篇日记", "发一篇日记", $pic, $url);

							$url = "http://www.familyday.com.cn/wx/wx.php?do=feed&wxkey=".$fromUsername;
							$pic = "http://www.familyday.com.cn/wx/images/feed-icon.jpg";
							$articles[] = makeArticleItem("全部家人动态", "全部家人动态", $pic, $url);
							$resultStr = makeArticles($fromUsername, $toUsername, $time, $msgType, "发布",$articles); 

						}else{
							$url = "http://www.familyday.com.cn/wx/wx.php?do=bind&wxkey=".$fromUsername;
							$pic = "http://www.familyday.com.cn/wx/images/bind.jpg";
							$articles[] = makeArticleItem("绑定微信帐号", "你还没有绑定微信号，请点击进入微信绑定页", $pic, $url);
							$resultStr = makeArticles($fromUsername, $toUsername, $time, $msgType, "绑定微信帐号",$articles);  
						}


					}elseif($keyword == "3"){
						$msgType = "news";
						$url = "http://www.familyday.com.cn/wx/wx.php?do=bind&wxkey=".$fromUsername;
						$pic = "http://www.familyday.com.cn/wx/images/bind.jpg";
						$articles[] = makeArticleItem("把微信号绑定在我家", "把微信号绑定在我家", $pic, $url);

						$url = "http://www.familyday.com.cn/wx/wx.php?do=reg&wxkey=".$fromUsername;
						$pic = "http://www.familyday.com.cn/wx/images/reg-icon.jpg";
						$articles[] = makeArticleItem("注册到我家帐号\n（会自动绑定微信）", "注册到我家帐号\n（会自动绑定微信）", $pic, $url);

						$url = "http://www.familyday.com.cn/wx/wx.php?do=invite&wxkey=".$fromUsername;
						$pic = "http://www.familyday.com.cn/wx/images/invite-icon.jpg";
						$articles[] = makeArticleItem("邀请好友", "邀请好友", $pic, $url);

						$url = "http://www.familyday.com.cn/wx/wx.php?do=feed&wxkey=".$fromUsername;
						$pic = "http://www.familyday.com.cn/wx/images/feed-icon.jpg";
						$articles[] = makeArticleItem("全部家人动态", "全部家人动态", $pic, $url);

						$url = "http://www.familyday.com.cn/wx/wx.php?do=feed&wxkey=".$fromUsername;
						$pic = "http://www.familyday.com.cn/wx/images/about-icon.jpg";
						$articles[] = makeArticleItem("关于我家的微信客户端", "关于我家的微信客户端", $pic, $url);

						$resultStr = makeArticles($fromUsername, $toUsername, $time, $msgType, "绑定微信帐号",$articles); 
					}else{
						$msgType = "text";
						$contentStr = "你好！欢迎来到“我家”，绑定微信机器人，帮你快速了解家人动态，还可以快速发布照片和文字，立即跟家人分享！

•回复【1】——查看家人动态；
•回复【2】——发表照片和日记；
•回复【3】——把微信绑定到我家；
•回复【4】——注册我家账号；
";
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}
                	echo $resultStr;
                }else{
                	echo "Input something...";
                }

        }else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}




?>