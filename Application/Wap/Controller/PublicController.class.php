<?php
namespace Wap\Controller;
class PublicController extends CommonController {
	
	
	//获取下一栏数据
	public function newsMore(){
		$comment = M('comment');
		$clollection = M('clollection');
		if(I('get.page')){
			$Action = new \Org\Util\More();
			$page = $Action->_get('page');
			$tag = $Action->_get('tag');
			$groupid = $Action->_get('groupid');
			if($tag!=""){
				$where['t_news.tag'] = intval($tag);
			}else if($groupid!=""){
			 	$where['t_news.groupid'] = intval($groupid);
			}
			if(!$groupid){
				$where['t_news.groupid'] = array(array('neq','4'),array('neq','5'));
			}
			$pages = $page*6;
			$list = M('news')
			->join("left join t_group on t_news.tag = t_group.id")
			->where($where)->order('t_news.ord asc,t_news.id desc')->limit("$pages,6")
			->field("t_news.*,t_group.title as tag_title")
			->select();
			
			$time = new \Org\Util\Date();
			foreach ($list as $key=>$val){
				$list[$key]['addtime'] = $time->timeDiff(intval($val['addtime']));
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
				code($phone);
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
				alertLocation("注册成功", "/wap");
			}else{
				alertBack("注册失败");
			}
		}
		$this->header();
		$this->footer();
		$this->display();
		
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
					alertLocation('登陆成功',"/Wap/");
				}else{
				alertBack('密码错误');
				}
			}else{
				alertBack('用户名不存在');
			}
		}
		$this->header();
		$this->footer();
		$this->display();
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
}