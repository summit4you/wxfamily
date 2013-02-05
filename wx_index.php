<?php
/**
  * wechat php test
  */


include_once( 'botutil.php' );


$welcome = '��ã���ӭ����"�Ҽ�"��΢�ſͻ��˵����ܻ����ˣ����԰���Ѹ�ٲ�ѯ���˶�̬����Ҳ����ֱ��ͨ��΢�ſ��ٷ�����Ƭ�����֣����������˷����а���ÿһ��!\n\n*
�ظ�"1"���鿴���˶�̬;\n(�ظ������˵绰���롱���Բ鿴ָ�����˵Ķ�̬��Ϣ��)\n*�ظ���2��,����;\n�ظ�"0"��ע�����˺�;';
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
        if($toUsername=="gh_51b7466306d9"){//΢��ԭʼid
            return      $welcome;//��ӭ��
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
                addUser2($fromUsername);
				
				if(!empty( $keyword ))
                {
					
					if ($key=='Hello2BizUser'){
						$msgType = "text";
						$contentStr = $this->welcome($toUsername);
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}elseif($keyword == "0"){
						$msgType = "news";
						$url = "http://www.familyday.com.cn/wx.php?do=bind&username=".$fromUsername;
						$pic = "http://www.familyday.com.cn/wx/template/css/images/logo2-2x.jpg";
						$articles[] = makeArticleItem("��΢���ʺ�", "��������΢�Ű�ҳ", $pic, $url);
						$resultStr = makeArticles($fromUsername, $toUsername, $time, $msgType, "��΢���ʺ�",$articles); 
					}else{
						$msgType = "text";
						$contentStr = $this->welcome($toUsername);
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