<?php
namespace Admin\Controller;

class GroupController extends CommonController {
	public function index(){
	    $pid = I('get.pid');
        $match = M('match');
        if($pid){
            $match_data = $match->where("id=$pid")->find();
            if($match_data){
                $this->assign('match_data',$match_data);
                $this->assign('pid',$match_data['id']);
                $where['pid'] = $match_data['id'];

            }
        }

		$table = M('group');
		$group = $table->select();
		$this->assign('data',$group);
        $match = M('match');
        $match_data = $match->order("id desc")->select();
        $this->assign('match_data',$match_data);
		$this->display();
	}
	
	public function add(){
        $group = M('Group');
        $match = M('match');
		if (IS_POST){
			$group = M('Group');
			$data = I('post.');
            $res = $match->find($data['matchid']);
            $data['typeid'] = $res['typeid'];
            $data['birth_star_time'] = strtotime($data['birth_star_time']);
            $data['birth_end_time'] = strtotime($data['birth_end_time']);
            $data['starttime'] = strtotime($data['starttime']);
            $data['endtime'] = strtotime($data['endtime']);
            $data['addtime'] = time();
			if ($group->add($data)){
				alertLocation('添加成功！', '/Admin/Group');
			}else {
				$this->error('添加失败！');
			}			
		}

        $match_data = $match->order("id desc")->select();
		$this->assign('match_data',$match_data);
		$this->display();
	}
	
	public function edit(){
		$id = I('get.id');
		$group = M('Group');
		$match = M('match');
		if (IS_POST){
			$where = I('post.');
            $where['birth_star_time'] = strtotime($where['birth_star_time']);
            $where['birth_end_time'] = strtotime($where['birth_end_time']);
            $where['starttime'] = strtotime($where['starttime']);
            $where['endtime'] = strtotime($where['endtime']);
			if ($group->save($where)){
				alertBack('修改成功！');		
			}else {
				$this->error('没有任何改动！');
			}
		}
		$data = $group->find($id);
		$this->assign($data);
        $match_data = $match->order("id desc")->select();
        $this->assign('match_data',$match_data);
		$this->display();
	}
	
	public function state(){
		$data = I('get.');
		$user = M('Group');
		if ($user->save($data)){
			$this->redirect("/Admin/Group/index/");
		}else {
			$this->error('没有任何修改！');
		}
	}
	
	public function ajaxstate(){
		$data = I('get.');
		$table = M('Group');
		if ($table->save($data)){
			echo 1;
		}else {
			echo 0;
		}
	}
	
	public function delete(){
		$post = implode(',',$_POST['id']);
		$user = M('Group');
		$data = $user->delete($post);
		if ($data){
			echo '删除成功！';
		}else {
			echo '删除失败！';
		}
	}
	
}