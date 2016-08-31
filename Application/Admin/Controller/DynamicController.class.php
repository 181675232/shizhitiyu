<?php
namespace Admin\Controller;

class DynamicController extends CommonController {
	
	public function index(){
		
		$table = M('dynamic'); // 实例化User对象
		
		//接收查询数据
		
		if (I('get.keyword')){
			$keyword = I('get.keyword');
			$data['t_dynamic.title'] = array('like',"%{$keyword}%");
		}
		if (I('get.type')){
			$this->assign('type',I('get.type'));
		}
		if (I('get.userid')){
			$user = M('user');
			$userid = I('get.userid');
			$user_data = $user->find($userid);
			if(!$user_data){
				alertLocation('数据出错！', '/Admin/User');
			}
			$data['t_dynamic.uid'] = $userid;
			$this->assign('userid',$userid);
		}
		$count      = $table->where($data)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		if(I('get.type')==2){
			$res = $table
			->join("left join t_user on t_dynamic.uid=t_user.id")
			->field("t_dynamic.*,t_user.username")
			->where($data)	->order('ord asc,isred desc,id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		}else{
			$res = $table
			->join("left join t_user on t_dynamic.uid=t_user.id")
			->field("t_dynamic.*,t_user.username")
			->where($data)	->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		}
		
		$this->assign('data',$res);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板			
	}
	
	public function add(){
		if (I('post.')){
			$where = I('post.');
			$table = M('Dynamic');

 			if ($where['user_simg']){
				$data['user_simg'] = $where['user_simg'];
				$data['user_desc'] = $where['user_desc'];
				unset($where['user_simg']);
				unset($where['user_desc']);
				unset($where['hidFocusPhoto']);
			} 
		
		
			$where['addtime'] = time();
			$res =$table->add($where);
			if ($res){
			 			if ($data['user_simg']){
							$img = M('img');
							$data_img['pid'] = $res;
							$data_img['uid'] = $where['uid'];
							for($i=0;$i<count($data['user_simg']);$i++){
								$data_img['type'] = 'dynamic';
								$data_img['simg'] = $data['user_simg'][$i];
								 $data_img['thumb_simg'] = create_thumb_complete($data['user_simg'][$i]);
								$data_img['title'] = $data['user_desc'][$i];
								$data_img['addtime'] = time();
								$img->add($data_img);
							}
						} 
				alertLocation('发布成功！', '/Admin/Dynamic/index/userid/'.$where['uid']);
			}else {
				$this->error('发布失败！');
			}
		}

		if (I('get.userid')){
			$user = M('user');
			$userid = I('get.userid');
			$user_data = $user->find($userid);
			if(!$user_data){
				alertLocation('数据出错！', '/Admin/User');
			}
			$this->assign('user_data',$user_data);
			$this->assign('userid',$userid);
		}else{
			alertLocation('数据出错！', '/Admin/User');
		}
		
		$this->display();

	}
	
	public function edit(){

		$id = I('get.id');
		if (IS_POST){
			$table = M('Dynamic');
			$where =I('post.');
 			if ($where['user_simg']){
				$data['user_simg'] = $where['user_simg'];
				$data['user_desc'] = $where['user_desc'];
				unset($where['user_simg']);
				unset($where['user_desc']);
				unset($where['hidFocusPhoto']);
			} 
			$res = $table->where("id = '{$where['id']}'")->find();
			
			$table->save($where);
 			if ($data['user_simg']){
				$img = M('img');
				$img->where("pid = '{$where['id']}' and type='dynamic'")->delete();
				$data_img['pid'] = $where['id'];
				$data_img['uid'] = $res['uid'];
				for($i=0;$i<count($data['user_simg']);$i++){
					$data_img['type'] = 'dynamic';
					$data_img['simg'] = $data['user_simg'][$i];
					 $data_img['thumb_simg'] = create_thumb_complete($data['user_simg'][$i]);
					$data_img['title'] = $data['user_desc'][$i];
					$data_img['addtime'] = time();
					$img->add($data_img);
				}
			} 
			alertBack('修改成功！');			
		}
		$table = M('Dynamic');
	//	$selfcity = M('selfcity');
		$img = M('img');
		$simg = $img->where("pid = $id and type='dynamic'")->order('id asc')->select();
		$this->assign('data_img',$simg);
		$data = $table->where("id = $id")->find();

		
		if(I('get.type')){
			$data['type'] = I('get.type');
		}
		if (I('get.userid')){
			$user = M('user');
			$userid = I('get.userid');
			$user_data = $user->find($userid);
			if(!$user_data){
				alertLocation('数据出错！', '/Admin/User');
			}
			$this->assign('user_data',$user_data);
			$this->assign('userid',$userid);
		}

		$this->assign($data);
		$this->display();
	}
	
	public function state(){
		$data = I('get.');		

		$str ='/';
		if (I('get.p')){
			$str.= 'p/'.I('get.p').'/';
		}
		if (I('get.keyword')){
			$str.= 'keyword/'.I('get.keyword').'/';
		}
		if (I('get.type')){
			$str.= 'type/'.I('get.type').'/';
		}
		if (I('get.userid')){
			$str.= 'userid/'.I('get.userid').'/';
		}
		$table = M('Dynamic');
		if ($table->save($data)){	
	$this->redirect("/Admin/Dynamic/index".$str);
		}else {
			$this->error('没有任何修改！');
		}
	}
	
	public function delete(){		
		$post = implode(',',$_POST['id']);	
		$table = M('Dynamic');
		$data = $table->delete($post);
		if ($data){
			echo '删除成功！';
		}else {
			echo '删除失败！';
		}
	}
	
	public function ajaxstate(){
		$data = I('get.');
		$table = M('Dynamic');
		if ($table->save($data)){
			echo 1;
		}else {
			echo 0;
		}
	}
	

	
	
	public function ajax(){
		if (!empty($_POST['param'])){
			$table = M('Dynamic');
			$data[$_POST['name']] = $_POST['param'];
			$return = $table->where($data)->find();
			if ($return){
				echo '手机号已存在！';
			}else {
				echo 'y';
			}
		}
	}
	
} 