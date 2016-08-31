<?php
namespace Home\Controller;

class PublicController extends CommonController {
	
	public function test(){
		$this->display();
	}
	public function test1(){
		$data['orderid'] = '1234567';
		$data['subject'] = '123';
		$data['price'] = '0.02';
		pay($data);
	}
	//获取下一栏数据
	public function newsMore(){
		$comment = M('comment');
		$clollection = M('clollection');
		if(I('get.page')){
			$Action = new \Org\Util\More();
			$page = $Action->_get('page');
			$tag = $Action->_get('tag');
			$groupid = $Action->_get('groupid');
			if($tag!="0"){
				$where['t_news.tag'] = intval($tag);
				$pages = $page*10;
			}else{
			 	$where['t_news.groupid'] = intval($groupid);
				$pages = $page*10+3;
			}
			$where['type'] = 1;
			$list = M('news')
			->join("left join t_group on t_news.tag = t_group.id")
			->where($where)->order('t_news.ord asc,t_news.id desc')->limit("$pages,10")
			->field("t_news.*,t_group.title as tag_title,t_group.title_short")
			->select();
			foreach ($list as $key=>$val){
				$list[$key]['count'] = $comment->where("newid='{$val['id']}' and state=2")->count(); 
				$coll = $clollection->where("pid='{$val['id']}' and userid='{$_SESSION['user_id']}'")->find();//是否收藏
				if($coll){
					$list[$key]['is_coll'] = 1;
				}else{
					$list[$key]['is_coll'] = 0;
				}
			}
		//	print_r($list);
			if($list){
				$Action->ajaxReturn($list);
			}else{
				echo 	1;
			}
		}
	}
	
	
//	图片验证码
	public function scode(){			
		$Verify = new \Think\Verify();
		$Verify->entry();
	}
	
	//发送手机验证码
	public function code(){
		if(I('get.phone')){
			$phone=I('get.phone');
			if(!checkPhone($phone)){
				echo 2;
				exit;
			}
			$user = M('user');
			$return = $user->where("phone=$phone")->find();
			if($return){
				echo 3;
				exit;
			}else{
				//code($phone);
				echo 1;
				exit;
			}
		}else {
			//echo 2;
		}
	}
	
/* 	//手机验证码验证
	public function phone_code(){
		if(I('get.')){
			if ((time() - session('time')) > 300){
				echo 2;
			}elseif (session('code') != I('get.code')){
				echo 3;
			}elseif (session('phone') != I('get.phone')){
				echo 4;
			}else{
				echo 1;
			}
		}else{
			echo 5;
		}
		
	} */
	
	
	//个人注册
	public function register(){
		header("Content-Type: text/html; charset=UTF-8");
		$user = M('user');
		if(I('post.')){
			$data = I('post.');
/* 			print_r($data);
			echo session('code');
			echo session('phone');
			exit; */
			$table = M('user');
			if ($table->where("phone='{$data['phone']}'")->find()){
				alertBack('该账号已注册');
			}
			if ((time() - session('time')) > 300){
				alertBack('验证码失效');
			}
			if (session('code') != I('post.phone_code')){
				alertBack('验证码错误');
			}
			if (session('phone') != I('post.phone')){
				alertBack('手机号提交错误');
			}
			$data['password'] = md5(trim(I('post.password')));
			$data['addtime'] = time();
			$res = $user->add($data);
			if($res){
				alertBack("注册成功");
			}else{
				alertBack("注册失败");
			}
		}
	}
	
	//	发送手机验证码ajax
	public function code_ajax(){
		if (!check_code($_POST['code'])){
			echo '图片验证码输入有误！';
			exit;
		}
		$user = M('user');
		$data['phone'] = $_POST['phone'];
		$data = $user->where($data)->find();
		if ($data){
			echo '该手机号已注册！';
			exit;
		}else {
				echo 1;
				exit;
		}
	}
	
	//登录
	public function login(){
		header("Content-Type: text/html; charset=UTF-8");
		if(I('post.')){
			$table = M('user');
		 	$phone=I('post.phone');
		 	if($phone=="请输入手机号码"){
		 		alertBack('用户名不存在');
		 	}
			$return = $table->where("phone=$phone")->find();
			if($return){
				$data['phone'] = $phone;
				$data['password'] = md5(I('post.password'));
				$user = $table->where($data)->find();
				if($user){
					session('user_name',$user['username']);
					session('user_id',$user['id']);
					session('type',$user['type']);
					jsReplace('登陆成功');
				}else{
				alertBack('密码错误');
				}
			}else{
				alertBack('用户名不存在');
			}
		}
		alertBack('请填写账号密码');
	}
	
	
	//	退出登录
	public function logout(){
		session(null);
		jsReplace('退出成功');
	
	}
	
//	登陆ajax
	public function login1(){
		if (!check_code($_POST['code'])){
			echo '验证码输入有误！';
			exit;
		}
		$user = M('user');
		$data['name'] = $_POST['name'];
		$data = $user->field('password')->where($data)->find();
		if (!$data){
			echo '用户名不存在！';
			exit;
		}else {
			if (md5($_POST['password']) != $data['password']){
				echo '密码输入错误！';
				exit;
			}else {
				echo 1;
				exit;
			}
		}
	}

	
	

	
//	上传图片
	function upload(){
		header("Content-Type:text/html; charset=utf-8");
		$upload = new \Think\Upload();// 实例化上传类
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->rootPath  =     './Public/upfile_user/'; // 设置附件上传根目录
		$upload->savePath  =     ''; // 设置附件上传（子）目录
		$upload->saveName = time().'_'.mt_rand(); //文件名
			
		// 上传文件
		$info   =   $upload->upload();
		if(!$info) {// 上传错误提示错误信息
			echo '上传失败！';
		}else{// 上传成功
			
			$data['status'] = 1;
			$data['msg'] = '文件上传成功！';
			$data['name'] = $info['Filedata']['name'];
			$data['path'] = '/Public/upfile_user/'.$info['Filedata']['savepath'].$info['Filedata']['savename'];
			$data['size'] = $info['Filedata']['size'];
			$data['ext'] = $info['Filedata']['ext'];
			if (!empty($_GET['IsThumbnail'])){
				$data['thumb'] = '/Public/upfile_user/'.$info['Filedata']['savepath'].'thumb_'.$info['Filedata']['savename'];
				$image = new \Think\Image();
				$image->open('.'.$data['path']);
				// 生成一个居中裁剪为150*150的缩略图并保存为thumb.jpg
				$image->thumb(150, 150,\Think\Image::IMAGE_THUMB_CENTER)->save('.'.$data['thumb']);
			}
			echo json_encode($data);
			exit;
		}
	}
	
	
	//qq登陆
	function login_qq(){
		header("Content-Type: text/html;charset=utf-8");
		if(isset($_GET['code'])){
			$res =  login_qq($_GET['code']);
			$user = M("user");
			$res1 = $user->where("qq_openid='{$res->openid}'")->find();
			if($res1){
				session('user_name',$res1['username']);
				session('user_id',$res1['id']);
				session('type',$res1['type']);
				echo $_GET['display'];
				if($_GET['display']=="wap"){
					alertLocation('登陆成功','/wap/');
				}else{
					alertLocation('登陆成功','/');
				}
			}else{
				$data['name'] = $res->nickname;
				$data['username'] = $res->nickname;
				if($res->gender=='男'){
					$data['sex'] = 1;
				}elseif($res->gender=='女'){
					$data['sex'] = 2;
				}
				$data['qq_openid'] = $res->openid;
				$data['simg'] = $res->figureurl_qq_2;
				$data['addtime'] = time();
				$res4 = $user->add($data);
				if($res4){
					$user5 = $user->find($res4);
					session('user_name',$user5['username']);
					session('user_id',$user5['id']);
					session('type',$user5['type']);
				if($_GET['display']=="wap"){
					alertLocation('登陆成功','/wap/');
				}else{
					alertLocation('登陆成功','/');
				}
				}else{
					
					if($_GET['display']=="wap"){
						alertLocation('登陆成功','/wap/');
					}else{
						alertLocation('登陆成功','/');
					}
				}
			} 
			 
	
		}
		alertLocation('数据出错','/');
	}
	
	
	
	public function erweima(){
	
		vendor("phpqrcode.phpqrcode");
		$id = I('get.id');
		$data = $this->url.'http://www.pangbo.tv/common/news/id/'.$id;
		// 纠错级别：L、M、Q、H
		$level = 'L';
		// 点的大小：1到10,用于手机端4就可以了
		$size = 10;
		// 下面注释了把二维码图片保存到本地的代码,如果要保存图片,用$fileName替换第二个参数false
		//$path = "images/";
		// 生成的文件名
		//$fileName = $path.$size.'.png';
		\QRcode::png($data, false, $level, $size);
	
	}
	
/* 	//安卓二维码
	public function android_erweima(){
		
		vendor("phpqrcode.phpqrcode");
		$data = $this->url.'http://www.pangbo.tv/Public/version/pangbo.apk';
		// 纠错级别：L、M、Q、H
		$level = 'L';
		// 点的大小：1到10,用于手机端4就可以了
		$size = 10;
		// 下面注释了把二维码图片保存到本地的代码,如果要保存图片,用$fileName替换第二个参数false
		//$path = "images/";
		// 生成的文件名
		//$fileName = $path.$size.'.png';
		\QRcode::png($data, false, $level, $size , '1%');
	} */
	
}

