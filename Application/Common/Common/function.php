<?php
/**
 * 按josn方式输出通信数据
 * @param integer $code 状态码
 * @param string $message 提示信息
 * @param array $data 数据
 */
function json($code,$message='',$data=array()){
	if (!is_numeric($code)){
		return '';
	}

	$result = array(
			'code' => $code,
			'message' => $message,
			'data' => $data
	);

	echo json_encode($result);
	exit;

}

/**
 * 获取当前页面完整URL地址
 */
function geturl() {
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	$path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
	return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}

//计算两地之间距离
function  powc($lat_a,$lng_a,$lat_b,$lng_b){
	$pk = 180 / 3.1415926;
	$a1 = $lat_a / $pk;
	$a2 = $lng_a / $pk;
	$b1 = $lat_b / $pk;
	$b2 = $lng_b / $pk;

	$t1 = cos($a1) * cos($a2) * cos($b1) * cos($b2);
	$t2 = cos($a1) * sin($a2) * cos($b1) * sin($b2);
	$t3 = sin($a1) * sin($b1);
	$tt = acos($t1 + $t2 + $t3);
	$distance = round((6366000 * $tt));
	return $distance;
}
/** 获取当前时间戳，精确到毫秒 */
function microtime_float()
{
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}
/** 格式化时间戳，精确到毫秒，x代表毫秒 */
function microtime_format($tag, $time)
{
	list($usec, $sec) = explode(".", $time);
	$date = date($tag,$usec);
	return str_replace('x', $sec, $date);
}
//数组分页
function array_page($array,$page,$count='15'){
	$page=(empty($page))?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面
	$start=($page-1)*$count; #计算每次分页的开始位置
	$totals=count($array);
	$pagedata=array();
	$pagedata=array_slice($array,$start,$count);
	return $pagedata;  #返回查询数据
}
//$curl post发送请求
function sendPostSMS($url,$data=array()){	
	
	$ch = curl_init();
	
	curl_setopt ($ch, CURLOPT_URL, $url);
	
	curl_setopt ($ch, CURLOPT_POST, 1);
	
	if($data != ''){
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	}
	
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
	
	curl_setopt($ch, CURLOPT_HEADER, false);
	
	$file_contents = curl_exec($ch);
	
	curl_close($ch);
	
	return $file_contents;
}
// 检测输入的验证码是否正确，$code为用户输入的验证码字符串
function check_code($code, $id = ''){ 
	$verify = new \Think\Verify(); 
	return $verify->check($code, $id);
}
//是否为空
function checkNull($_data) {
	if (trim($_data) == '') return true;
	return false;
}

//数据是否为数字
function checkNum($_data) {
	if (is_numeric($_data)) return true;
	return false;
}

//二维数组去重
function take($arr){
	$tmp_array = array();
	$new_array = array();
	foreach($arr as $k => $val){
		$hash = md5(json_encode($val));
		if (!in_array($hash, $tmp_array)) {
			$tmp_array[] = $hash;
			$new_array[] = $val;
		}
	}
	return $new_array;
}


//长度是否合法
function checkLength($_data, $_length, $_flag) {
	if ($_flag == 'min') {
		if (mb_strlen(trim($_data),'utf-8') < $_length) return true;
		return false;
	} elseif ($_flag == 'max') {
		if (mb_strlen(trim($_data),'utf-8') > $_length) return true;
		return false;
	} elseif ($_flag == 'equals') {
		if (mb_strlen(trim($_data),'utf-8') != $_length) return true;
		return false;
	} else {
		alertBack('EROOR：参数传递的错误，必须是min,max！');
	}
}
//验证电子邮件
function checkEmail($_data) {
	if (preg_match('/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/',$_data)) return true;
	return false;
}

//验证手机
function checkPhone($_data) {
	if (preg_match('/^((1[3,5,8][0-9])|(14[5,7])|(17[0,6,7,8]))\d{8}$/',$_data)) return true;
	return false;
}

//账号
function checkUser($_data) {
	if (preg_match('/^[a-zA-Z0-9]+$/',$_data)) return true;
	return false;
}

//数据是否一致
function checkEquals($_data, $_otherdate) {
	if (trim($_data) == trim($_otherdate)) return true;
	return false;
}


//弹窗跳转
function alertLocation($_info, $_url) {
	if (!empty($_info)) {
		echo "<script type='text/javascript'>alert('$_info');location.href='$_url';</script>";
		exit();
	} else {
		header('Location:'.$_url);
		exit();
	}
}

//弹窗返回
function alertBack($_info) {
	echo "<script type='text/javascript'>alert('$_info');history.back();</script>";
	exit();
}

//弹窗返回刷新
function alertReplace($_info) {
	echo "<script type='text/javascript'>alert('$_info');location.replace(document.referrer);</script>";
	exit();
}

//不弹窗返回刷新
function jsReplace() {
	echo "<script type='text/javascript'>location.replace(document.referrer);</script>";
	exit();
}

//br换p
function nl2p($string, $line_breaks = true, $xml = true){
    // Remove existing HTML formatting to avoid double-wrapping things
    $string = str_replace(array('<p>', '</p>', '<br>', '<br />'), '', $string);
     
    // It is conceivable that people might still want single line-breaks
    // without breaking into a new paragraph.
    if ($line_breaks == true)
        return '<p>'.preg_replace(array("/([\n]{2,})/i", "/([^>])\n([^<])/i"), array("</p>\n<p>", '<br'.($xml == true ? ' /' : '').'>'), trim($string)).'</p>';
    else
        return '<p>'.preg_replace("/([\n]{1,})/i", "</p>\n<p>", trim($string)).'</p>';
}

/**
 * 截取UTF-8编码下字符串的函数
 */
function sub_str($str, $length = 0, $append = true){
    $str = trim($str);
    $strlength = strlen($str);

    if ($length == 0 || $length >= $strlength){
        return $str;
    }
    elseif ($length < 0)
    {
        $length = $strlength + $length;
        if ($length < 0)
        {
            $length = $strlength;
        }
    }

    if (function_exists('mb_substr')){
        $newstr = mb_substr($str, 0, $length, 'utf-8');
    }
    elseif (function_exists('iconv_substr'))
    {
        $newstr = iconv_substr($str, 0, $length, 'utf-8');
    }
    else
    {
        //$newstr = trim_right(substr($str, 0, $length));
        $newstr = substr($str, 0, $length);
    }

   if ($append && $str != $newstr)
    {
        $newstr .= '...';
    }
    return $newstr;
}


function sub_str1($str, $length = 0, $append = true){
	$str = trim($str);
	$strlength = strlen($str);

	if ($length == 0 || $length >= $strlength){
		return $str;
	}
	elseif ($length < 0)
	{
		$length = $strlength + $length;
		if ($length < 0)
		{
			$length = $strlength;
		}
	}

	if (function_exists('mb_substr')){
		$newstr = mb_substr($str, 0, $length, 'utf-8');
	}
	elseif (function_exists('iconv_substr'))
	{
		$newstr = iconv_substr($str, 0, $length, 'utf-8');
	}
	else
	{
		//$newstr = trim_right(substr($str, 0, $length));
		$newstr = substr($str, 0, $length);
	}

	if ($append && $str != $newstr)
	{
		//$newstr .= '...';
	}
	return $newstr;
}
//验证码

function yzm($phone)
{
	$vcodes = '';
	for($i=0;$i<6;$i++){$authnum=rand(1,9);$vcodes.=$authnum;}//生成验证码
	$username = 'caimantangcn';		//用户账号
	$password = 'caimantang123';	//密码
	$apikey = 'e1127a31a9dd2dee4ec9cc325da5b580';//密码
	$mobile	 = $phone;	//号手机码

	$content = '您的短信验证码是：'.$vcodes.'，本次验证码有效期为5分钟。【Jolly着迷】';//内容
	// 		$this->session->set_userdata('time', time());
	// 		$this->session->set_userdata('mcode', $vcodes);//将content的值保存在session中
	$result = sendSMS($username,$password,$mobile,$content,$apikey);
	$data['code'] = $vcodes;
	session('phone',$phone);
	session('time',time());
	session('code',$vcodes);
	return json('200','成功！',$data);
}

//验证码

function code($phone)
{
	$vcodes = '';
	for($i=0;$i<6;$i++){$authnum=rand(1,9);$vcodes.=$authnum;}//生成验证码
	$username = 'caimantangcn';		//用户账号
	$password = 'caimantang123';	//密码
	$apikey = 'e1127a31a9dd2dee4ec9cc325da5b580';//密码
	$mobile	 = $phone;	//号手机码

	$content = '您的短信验证码是：'.$vcodes.'，本次验证码有效期为5分钟。【Jolly着迷】';//内容
	session('time',time());
	session('code',$vcodes);
	session('phone',$phone);
	$result = sendSMS($username,$password,$mobile,$content,$apikey);
}

function sendSMS($username,$password,$mobile,$content,$apikey)
{
	$url = 'http://m.5c.com.cn/api/send/?';
	$data = array
	(
			'username'=>$username,					//用户账号
			'password'=>$password,				//密码
			'mobile'=>$mobile,					//号码
			'content'=>$content,				//内容
			'apikey'=>$apikey,				    //apikey
	);
	$result= curlSMS($url,$data);			//POST方式提交
	return $result;
}

function curlSMS($url,$post_fields=array()){
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 3600); //60秒
	curl_setopt($ch, CURLOPT_HEADER,1);
	curl_setopt($ch, CURLOPT_REFERER,'http://www.yourdomain.com');
	curl_setopt($ch,CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$post_fields);
	$data = curl_exec($ch);
	curl_close($ch);
	$res = explode("\r\n\r\n",$data);
	return $res[2];
}


//生成缩略图
function create_thumb($oprn){
	$image = new \Think\Image();
	$image->open('./Public/upfile/'.$oprn);
	$width = $image->width(); // 返回图片的宽度
	$height = $image->height(); // 返回图片的宽度
	// 按照原图的比例生成一个最大为400*400的缩略图并保存为thumb.jpg
	$image->thumb(600, 600)->save('./Public/thumb/'.$oprn);
	return '/Public/thumb/'.$oprn;
}

//生成缩略图
function create_thumb_complete($oprn){
	$image = new \Think\Image();
	$image->open('.'.$oprn);
	$array['width'] = $image->width(); // 返回图片的宽度
	$array['height'] = $image->height(); // 返回图片的宽度
	// 按照原图的比例生成一个最大为400*400的缩略图并保存为thumb.jpg
	$arr = explode('/', $oprn);

	if (!is_dir('./Public/thumb/'.$arr[3])){
		mkdir('./Public/thumb/'.$arr[3],0777);
	}
	$image->thumb(600, 600)->save('./Public/thumb/'.$arr[3].'/'.$arr[4]);
	return  '/Public/thumb/'.$arr[3].'/'.$arr[4];
}


function getFirstChar($string) {
	$firstCharOrd = ord(strtoupper($string{0}));
	if (($firstCharOrd >= 65 && $firstCharOrd <= 91) || ($firstCharOrd >= 48 && $firstCharOrd <= 57))
		return strtoupper($string{0});
	$s = iconv("UTF-8","gb2312", $string);
	$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
	if($asc >=- 20319 && $asc <=- 20284)
		return "A";
	if($asc >=- 20283 && $asc <=- 19776)
		return "B";
	if($asc >=- 19775 && $asc <=- 19219)
		return "C";
	if($asc >=- 19218 && $asc <=- 18711)
		return "D";
	if($asc >=- 18710 && $asc <=- 18527)
		return "E";
	if($asc >=- 18526 && $asc <=- 18240)
		return "F";
	if($asc >=- 18239 && $asc <=- 17923)
		return "G";
	if($asc >=- 17922 && $asc <=- 17418)
		return "H";
	if($asc >=- 17417 && $asc <=- 16475)
		return "J";
	if($asc >=- 16474 && $asc <=- 16213)
		return "K";
	if($asc >=- 16212 && $asc <=- 15641)
		return "L";
	if($asc >=- 15640 && $asc <=- 15166)
		return "M";
	if($asc >=- 15165 && $asc <=- 14923)
		return "N";
	if($asc >=- 14922 && $asc <=- 14915)
		return "O";
	if($asc >=- 14914 && $asc <=- 14631)
		return "P";
	if($asc >=- 14630 && $asc <=- 14150)
		return "Q";
	if($asc >=- 14149 && $asc <=- 14091)
		return "R";
	if($asc >=- 14090 && $asc <=- 13319)
		return "S";
	if($asc >=- 13318 && $asc <=- 12839)
		return "T";
	if($asc >=- 12838 && $asc <=- 12557)
		return "W";
	if($asc >=- 12556 && $asc <=- 11848)
		return "X";
	if($asc >=- 11847 && $asc <=- 11056)
		return "Y";
	if($asc >=- 11055 && $asc <=- 10247)
		return "Z";
	return null;
}



//检测参数
/**
 *@param name string or array 参数名称,字符串间以,隔开
 * @param type 传参方式 默认post
 * @param is_need 是否为必传参数， true 为是(默认) false 为否
 * @return  success:array error:json 且停止执行
 */
function check_param($name,$is_need=true,$type="post"){
	if(!is_array($name)){
		$name	= explode(',',$name);
	}
	$data	= '';
	foreach($name as $v){
		$str	= $type.'.'.$v;
		$param	= I($str);
		if(($param == '') && $is_need){
			$data .= " $v";
		}
		// 		else{
		// 			if($param){
		// 				$data[$v]	= $param;
		// 			}
		// 		}
	}
	return $data;
}




//百度翻译

define("CURL_TIMEOUT",   10);
define("URL",            "http://api.fanyi.baidu.com/api/trans/vip/translate");
define("APP_ID",         "20160126000009418"); //替换为您的APPID
define("SEC_KEY",        "kDXwYTwcm2Pbdko_XEFD");//替换为您的密钥

//翻译入口
function translate($query, $from, $to)
{
	$args = array(
			'q' => $query,
			'appid' => APP_ID,
			'salt' => rand(10000,99999),
			'from' => $from,
			'to' => $to,

	);
	$args['sign'] = buildSign($query, APP_ID, $args['salt'], SEC_KEY);
	$ret = call(URL, $args);
	$ret = json_decode($ret, true);
	return $ret;
}

//加密
function buildSign($query, $appID, $salt, $secKey)
	{/*{{{*/
	$str = $appID . $query . $salt . $secKey;
	$ret = md5($str);
	return $ret;
}/*}}}*/

//发起网络请求
function call($url, $args=null, $method="post", $testflag = 0, $timeout = CURL_TIMEOUT, $headers=array())
{/*{{{*/
	$ret = false;
	$i = 0;
	while($ret === false)
	{
		if($i > 1)
			break;
		if($i > 0)
		{
			sleep(1);
		}
		$ret = callOnce($url, $args, $method, false, $timeout, $headers);
		$i++;
	}
	return $ret;
}/*}}}*/

function callOnce($url, $args=null, $method="post", $withCookie = false, $timeout = CURL_TIMEOUT, $headers=array())
{/*{{{*/
	$ch = curl_init();
	if($method == "post")
	{
		$data = convert($args);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_POST, 1);
	}
	else
	{
		$data = convert($args);
		if($data)
		{
			if(stripos($url, "?") > 0)
			{
				$url .= "&$data";
			}
			else
			{
				$url .= "?$data";
			}
		}
	}
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	if(!empty($headers))
	{
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	}
	if($withCookie)
	{
		curl_setopt($ch, CURLOPT_COOKIEJAR, $_COOKIE);
	}
	$r = curl_exec($ch);
	curl_close($ch);
	return $r;
}/*}}}*/

function convert(&$args)
	{/*{{{*/
	$data = '';
	if (is_array($args))
	{
		foreach ($args as $key=>$val)
		{
			if (is_array($val))
			{
				foreach ($val as $k=>$v)
				{
					$data .= $key.'['.$k.']='.rawurlencode($v).'&';
				}
			}
			else
			{
				$data .="$key=".rawurlencode($val)."&";
			}
		}
		return trim($data, "&");
	}
	return $args;
}/*}}}*/


