<?php




include_once( 'simple_html_dom.php' );

function makeText($fromUsername, $toUsername, $time, $msgType, $contentStr)
{
	$textTpl = "<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[%s]]></MsgType>
				<Content><![CDATA[%s]]></Content>
				<FuncFlag>0<FuncFlag>
				</xml>"; 
	if (empty($contentStr)) $contentStr = "抱歉，当前网络繁忙，请稍后再试.";
	return sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
}

function makeArticleItem($title, $discription, $picUrl, $url){
	
	$aTpl = "<item>
			 <Title><![CDATA[%s]]></Title>
			 <Discription><![CDATA[%s]]></Discription>
			 <PicUrl><![CDATA[%s]]></PicUrl>
			 <Url><![CDATA[%s]]></Url>
			 </item>
			 ";

	return sprintf($aTpl, $title, $discription, $picUrl, $url);
	
}

function makeArticles($fromUsername, $toUsername, $time, $msgType, $contentStr, $Articles, $startflag=0){

	$tpl = "<xml>
			 <ToUserName><![CDATA[%s]]></ToUserName>
			 <FromUserName><![CDATA[%s]]></FromUserName>
			 <CreateTime>%s</CreateTime>
			 <MsgType><![CDATA[%s]]></MsgType>
			 <Content><![CDATA[%s]]></Content>
			 <ArticleCount>%d</ArticleCount>
			 <Articles>
			 %s
			 </Articles>
			 <FuncFlag>0</FuncFlag>
			</xml>   ";
	;

	return sprintf($tpl, $fromUsername, $toUsername, $time, $msgType, $contentStr, count($Articles), implode('\n', $Articles), $startflag);
}

function curPageURL() 
{
    $pageURL = 'http';

    if ($_SERVER["HTTPS"] == "on") 
    {
        $pageURL .= "s";
    }
    $pageURL .= "://";

    if ($_SERVER["SERVER_PORT"] != "80") 
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } 
    else 
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}



function weekday($time)
{
    if(is_numeric($time))
    {
        return date('w', $time);
    }

    return false;

}

function getDayTime($date=""){
	if (empty($date))
		$data =  date("Y-m-d",time());
	$year=((int)substr($data,0,4));//取得年份
	$month=((int)substr($data,5,2));//取得月份
	$day=((int)substr($data,8,2));//取得几号
	return  mktime(0,0,0,$month,$day,$year);
}

function sortByCount($a, $b) {

	if (count($a) == count($b)) {
		return 0;
	} else {
		return (count($a) > count($b)) ? -1 : 1;
	}
}

function html2text($str){  
	$str = preg_replace("/<style .*?<\/style>/is", "", $str);  $str = preg_replace("/<script .*?<\/script>/is", "", $str);  
	$str = preg_replace("/<br \s*\/?\/>/i", "\n", $str);  
	$str = preg_replace("/<\/?p>/i", "\n\n", $str);  
	$str = preg_replace("/<\/?td>/i", "\n", $str);  
	$str = preg_replace("/<\/?div>/i", "\n", $str);  
	$str = preg_replace("/<\/?blockquote>/i", "\n", $str);  
	$str = preg_replace("/<\/?li>/i", "\n", $str);  
	$str = preg_replace("/\&nbsp\;/i", " ", $str);  
	$str = preg_replace("/\&nbsp/i", " ", $str);  
	$str = preg_replace("/\&amp\;/i", "&", $str);  
	$str = preg_replace("/\&amp/i", "&", $str);    
	$str = preg_replace("/\&lt\;/i", "<", $str);  
	$str = preg_replace("/\&lt/i", "<", $str);  
	$str = preg_replace("/\&ldquo\;/i", '"', $str);  
	$str = preg_replace("/\&ldquo/i", '"', $str);  
	$str = preg_replace("/\&lsquo\;/i", "'", $str);  
	$str = preg_replace("/\&lsquo/i", "'", $str);  
	$str = preg_replace("/\&rsquo\;/i", "'", $str);  
	$str = preg_replace("/\&rsquo/i", "'", $str);  
	$str = preg_replace("/\&gt\;/i", ">", $str);   
	$str = preg_replace("/\&gt/i", ">", $str);   
	$str = preg_replace("/\&rdquo\;/i", '"', $str);   
	$str = preg_replace("/\&rdquo/i", '"', $str);   
	$str = strip_tags($str);  
	$str = html_entity_decode($str, ENT_QUOTES,  "UTF-8");  
	$str = preg_replace("/\&\#.*?\;/i", "", $str);          

	return $str;
}

function capi_mkjson($response='', $callback=''){
	
	if ($callback){
		header('Cache-Control: no-cache, must-revalidate');
		header('Content-Type: text/javascript;charset=utf-8');
		echo $callback.'('.json_encode($response).');';
	}else{
		// application/x-json will make error in iphone, so I use the text/json
		// instead of the orign mine type
		header('Cache-Control: no-cache, must-revalidate');
		header('Content-Type: text/json;'); 
		
		echo json_encode($response);

	}
	exit();
}

function capi_showmessage_by_data($msgkey, $code=1, $data=array()){
	obclean();

	//去掉广告
	$_SGLOBAL['ad'] = array();
	
	//语言
	include_once(S_ROOT.'./language/lang_showmessage.php');
	if(isset($_SGLOBAL['msglang'][$msgkey])) {
		$message = lang_replace($_SGLOBAL['msglang'][$msgkey], $values);
	} else {
		$message = $msgkey;
	}
	$r = array();
	$r['code'] = $code;
	$r['data'] = $data;
	$r['msg'] = $message;
	$r['action'] = $msgkey;
	capi_mkjson($r, $_REQUEST['callback'] );
}


function getAuth(){
	$jsonurl = "http://www.familyday.com.cn/wx/wx_auth.php";
	$json = file_get_contents($jsonurl,0,null,null);
	return $json;
}
?>