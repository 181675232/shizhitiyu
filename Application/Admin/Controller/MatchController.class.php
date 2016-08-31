<?php
namespace Admin\Controller;

class MatchController extends CommonController {
	
	public function index(){
		
		$table = M('match'); // 实例化User对象
        $type = M('type');
        //接收查询数据
		
		if (I('get.keyword')){
			$keyword = I('get.keyword');
			$data['t_match.title'] = array('like',"%{$keyword}%");
		}
		if (I('get.type')){
            $data['t_match.pid'] = I('get.type');
			$this->assign('type',I('get.type'));
		}

		$count      = $table->where($data)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性

		$res = $table->where($data)	->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $type_data = $type->order("id asc")->select();

		$this->assign('data',$res);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
        $this->assign('type_data',$type_data);
		$this->display(); // 输出模板			
	}
	
	public function add(){
		if (I('post.')){
			$where = I('post.');
			$table = M('Match');
            $where['sign_start_time'] = strtotime($where['sign_start_time']);//比赛报名开始时间
            $where['sign_end_time'] = strtotime($where['sign_end_time']);//比赛报名结束时间
            $where['starttime'] = strtotime($where['starttime']);//比赛开始时间
            $where['endtime'] = strtotime($where['endtime']);//比赛结束时间
			$where['addtime'] = time();
			$res =$table->add($where);
            if($res){
				alertLocation('发布成功！', '/Admin/Match/index/');
			}else {
				$this->error('发布失败！');
			}
		}
        $type = M('type');
        $type_data = $type->order("id asc")->select();
        $this->assign('type_data',$type_data);
		$this->display();

	}
	
	public function edit(){

		$id = I('get.id');
        $table = M('Match');
        $type = M('type');
		if (IS_POST){
			$where =I('post.');
            $where['sign_start_time'] = strtotime($where['sign_start_time']);//比赛报名开始时间
            $where['sign_end_time'] = strtotime($where['sign_end_time']);//比赛报名结束时间
            $where['starttime'] = strtotime($where['starttime']);//比赛开始时间
            $where['endtime'] = strtotime($where['endtime']);//比赛结束时间
			$res = $table->where("id = '{$where['id']}'")->find();
			
			$table->save($where);
			alertBack('修改成功！');			
		}

		$data = $table->where("id = $id")->find();
        $type_data = $type->order("id asc")->select();
		$this->assign($data);
		$this->assign("type_data",$type_data);
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
		$table = M('Match');
		if ($table->save($data)){	
	$this->redirect("/Admin/Match/index".$str);
		}else {
			$this->error('没有任何修改！');
		}
	}
	
	public function delete(){		
		$post = implode(',',$_POST['id']);	
		$table = M('Match');
		$data = $table->delete($post);
		if ($data){
			echo '删除成功！';
		}else {
			echo '删除失败！';
		}
	}
	
	public function ajaxstate(){
		$data = I('get.');
		$table = M('Match');
		if ($table->save($data)){
			echo 1;
		}else {
			echo 0;
		}
	}
	

	
	
	public function ajax(){
		if (!empty($_POST['param'])){
			$table = M('Match');
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