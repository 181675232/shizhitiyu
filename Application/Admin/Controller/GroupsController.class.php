<?php
namespace Admin\Controller;

class GroupsController extends CommonController {
	
	public function index(){
		
		$table = M('groups'); // 实例化User对象
		//接收查询数据
		if (I('get.keyword')){
			$keyword = I('get.keyword');
			$data['t_groups.title'] = array('like',"%{$keyword}%");
		}
		if (I('get.verify')){
			$data['t_groups.state'] = I('get.verify');
			$this->assign('verify',I('get.verify'));
		}
		$count      = $table->where($data)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$res = $table->field('t_groups.*,t_user.username,t_user.phone')
		->join('left join t_user on t_user.id = t_groups.uid')
		->where($data)	->order('state asc,id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('data',$res);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板			
	}
	
	public function add(){
		$type = I('get.type')?I('get.type'):0;
		
		if (I('post.')){
			$where = array_filter(I('post.'));
			$table = M('news');
			if ($_SESSION['level'] != 0){
				if ($_SESSION['provinceid']){
					$where['provinceid'] = $_SESSION['provinceid'];
				}
				if ($_SESSION['cityid']){
					$where['cityid'] = $_SESSION['cityid'];
				}
				if ($_SESSION['areaid']){
					$where['areaid'] = $_SESSION['areaid'];
				}
			}
			if ($where['tag']){
				$where['tag'] = implode(',', $where['tag']);
			}
			if ($where['user_simg']){
				$data['user_simg'] = $where['user_simg'];
				$data['user_desc'] = $where['user_desc'];
				unset($where['user_simg']);
				unset($where['user_desc']);
				unset($where['hidFocusPhoto']);
			}
			if ($where['content']){
				$where['content'] = stripslashes(htmlspecialchars_decode($_POST['content']));
			}
			$where['addtime'] = time();
			$res =$table->add($where);
			if ($res){
				if ($data['user_simg']){
					$img = M('img');
					$data_img['pid'] = $res;
					for($i=0;$i<count($data['user_simg']);$i++){
						$data_img['type'] = 'news';
						$data_img['simg'] = $data['user_simg'][$i];
						$data_img['title'] = $data['user_desc'][$i];
						$data_img['addtime'] = time();
						
						$img->add($data_img);
					}
				}
				alertLocation('添加成功！', '/Admin/News');
			}else {
				$this->error('添加失败！');
			}
		}
		$group = M('group');
		$selfcity = M('selfcity');
		$gourpdata = $group->where('pid = 0')->select();
		$tag = $group->where("pid = $type")->select();
		$city = $selfcity->order('isred desc,id desc')->select();
		$this->assign('tag',$tag);
		$this->assign('group',$gourpdata);
		$this->assign('city',$city);
		$this->assign('type',$type);
		$this->display();

	}
	
	public function edit(){
		$id = I('get.id');
		if (IS_POST){
			$table = M('news');
			$where = array_filter(I('post.'));
			if ($where['tag']){
				$where['tag'] = implode(',', $where['tag']);
			}
			if ($where['user_simg']){
				$data['user_simg'] = $where['user_simg'];
				$data['user_desc'] = $where['user_desc'];
				unset($where['user_simg']);
				unset($where['user_desc']);
				unset($where['hidFocusPhoto']);
			}
			if ($where['content']){
				$where['content'] = stripslashes(htmlspecialchars_decode($_POST['content']));
			}
			$table->save($where);
			if ($data['user_simg']){
				$img = M('img');
				$img->where("pid = '{$where['id']}'")->delete();
				$data_img['pid'] = $where['id'];
				for($i=0;$i<count($data['user_simg']);$i++){
					$data_img['type'] = 'news';
					$data_img['simg'] = $data['user_simg'][$i];
					$data_img['title'] = $data['user_desc'][$i];
					$data_img['addtime'] = time();
					$img->add($data_img);
				}
			}
			alertBack('修改成功！');			
		}
		$table = M('news');
		$group = M('group');
		$selfcity = M('selfcity');
		$img = M('img');
		$simg = $img->where("pid = $id")->order('id asc')->select();
		$this->assign('data_img',$simg);
		$data = $table->where("id = $id")->find();
		$data['tag'] = explode(',', $data['tag']);
		$gourpdata = $group->where('pid = 0')->select();
		$tag = $group->where("pid = '{$data['gourpid']}'")->select();
		$city = $selfcity->order('isred desc,id desc')->select();
		$this->assign('tags',$tag);
		$this->assign('group',$gourpdata);
		$this->assign('city',$city);
		$this->assign($data);
		$this->display();
	}
	
	public function state(){
		$data = I('get.');
		$data['state'] = 2;
		$table = M('groups');
		if ($table->save($data)){
			$data = $table->find(I('get.id'));
			$user = M('user');
			$userinfo = $user->find($data['uid']);
			$groupuser = M('groupuser');
			$where['group_id'] = $data['id'];
			$where['username'] = $userinfo['username'];
			$where['user_id'] = $data['uid'];
			$where['level'] = 5;
			$where['addtime'] = time();
			$res = $groupuser->add($where);
			if ($res){
				$rongyun = new  \Org\Util\Rongyun($this->appKey,$this->appSecret);
				$r = $rongyun->groupCreate($data['uid'], $data['id'], $data['title']);
				if($r){
					$rong = json_decode($r);
					if($rong->code == 200){
						alertLocation('操作成功', "/Admin/Groups");
					}else {
						alertBack('系统内部错误1');
					}
				}else {
					alertBack('系统内部错误2');
				}
			}else {
				alertBack('添加成员失败');
			}		
		}else {
			$this->error('没有任何修改！');
		}
	}
	
	public function delete(){		
		$post = implode(',',$_POST['id']);	
		$table = M('groups');
		$data = $table->delete($post);
		if ($data){
			echo '删除成功！';
		}else {
			echo '删除失败！';
		}
	}
	
	public function ajaxstate(){
		$data = I('get.');
		$table = M('groups');
		if ($table->save($data)){
			echo 1;
		}else {
			echo 0;
		}
	}
	
	public function ajax(){
		if (!empty($_POST['param'])){
			$table = M('groups');
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