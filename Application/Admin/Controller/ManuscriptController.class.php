<?php
namespace Admin\Controller;

class ManuscriptController extends CommonController {
	
	public function index(){
		
		$table = M('news'); // 实例化User对象
		$group = M('group');
		//接收查询数据
		
		if (I('get.keyword')){
			$keyword = I('get.keyword');
			$data['t_news.title'] = array('like',"%{$keyword}%");
		}

		if (I('get.tag') and I('get.tag')!=""){
			$data['t_news.tag'] = I('get.tag');
			$this->assign('tag',I('get.tag'));
		}
		if (I('get.state')){
			$data['t_news.state'] = I('get.state');
			$this->assign('state',I('get.state'));
		}else{
			$data['t_news.state'] = array('neq','4');
		}

		$data['t_news.userid'] = array('neq','0');
		$count      = $table->where($data)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$res = $table
		->join('left join t_user ON t_news.userid = t_user.id')
		->where($data)	->field("t_news.*,t_user.username,t_user.name")
		->order('t_news.state asc,t_news.addtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$group_tag = $group->where("pid = '4'")->order("ord asc,id asc")->select();

		$this->assign('group_tag',$group_tag);
		$this->assign('data',$res);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板			
	}
	
	public function add(){
		$type = I('get.type')?I('get.type'):1;
		
		
		if (I('post.')){
			$where = array_filter(I('post.'));
			
			$table = M('news');

/* 			if ($where['user_simg']){
				$data['user_simg'] = $where['user_simg'];
				$data['user_desc'] = $where['user_desc'];
				unset($where['user_simg']);
				unset($where['user_desc']);
				unset($where['hidFocusPhoto']);
			} */
			
			if ($where['lawyer']){
				$where['lawyer'] = implode(',', $where['lawyer']);
			}
			
			if ($where['content']){
				$where['content'] = stripslashes(htmlspecialchars_decode($_POST['content']));
			}
			$where['addtime'] = time();
			$where['state'] = 2; 
			$res =$table->add($where);
			if ($res){
/* 				if ($data['user_simg']){
					$img = M('img');
					$data_img['pid'] = $res;
					for($i=0;$i<count($data['user_simg']);$i++){
						$data_img['type'] = 'news';
						$data_img['simg'] = $data['user_simg'][$i];
						$data_img['title'] = $data['user_desc'][$i];
						$data_img['addtime'] = time();
						
						$img->add($data_img);
					}
				} */
				alertLocation('添加成功！', '/Admin/News');
			}else {
				$this->error('添加失败！');
			}
		}
		$group = M('group');
		$law = M('law');
		$gourpdata = $group->where('pid = 0')->select();
		$lawdata = $law->order("ord asc,id asc")->select();
		$tag = $group->select();
		$this->assign('group',$gourpdata);
		$this->assign('law',$lawdata);
		$this->assign('type',$type);
		$this->display();

	}
	
	public function edit(){

		$id = I('get.id');
		if (IS_POST){
			$table = M('news');
			$where = array_filter(I('post.'));
/* 			if ($where['user_simg']){
				$data['user_simg'] = $where['user_simg'];
				$data['user_desc'] = $where['user_desc'];
				unset($where['user_simg']);
				unset($where['user_desc']);
				unset($where['hidFocusPhoto']);
			} */
			if ($where['content']){
				$where['content'] = stripslashes(htmlspecialchars_decode($_POST['content']));
			}
			if ($where['lawyer']){
				$where['lawyer'] = implode(',', $where['lawyer']);
			}
			$table->save($where);
/* 			if ($data['user_simg']){
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
			} */
			alertBack('修改成功！');			
		}
		$table = M('news');
		$group = M('group');
		$lawyer = M('lawyer');
		$law = M('law');
	//	$selfcity = M('selfcity');
	//	$img = M('img');
	//	$simg = $img->where("pid = $id")->order('id asc')->select();
	//	$this->assign('data_img',$simg);
		$data = $table->where("id = $id")->find();
		$data['lawyer'] = explode(',', $data['lawyer']);
		$tag = $group->where("pid = 4")->select();
		$lawyerdata = $lawyer->where("lawid = '{$data['lawid']}'")->select();
		//$city = $selfcity->order('isred desc,id desc')->select();
		
		$lawdata = $law->order("ord asc,id asc")->select();
		$this->assign('law',$lawdata);
		$this->assign('tags',$tag);
		$this->assign('lawyerdata',$lawyerdata);
		//$this->assign('city',$city);
		$this->assign($data);
		$this->display();
	}
	
	
	
	public function check(){
		header("Content-Type: text/html; charset=UTF-8");
		$news = M('news');
		if(I('get.id')){
			$data['id'] = I('get.id');
			$data['state'] = 2;
			if($news->save($data)){
				alertReplace('操作成功！');
			}else{
				$this->error('没有任何修改！');
			}
		}
		if(I('post.id')){
			$data['id'] = I('post.id');
			$data['state'] = 3;
			$data['reason'] = I('post.reason');
			$res = $news->save($data);
			if($res){
				$data1['status'] = 1;
				$data1['msg'] = '提示：处理成功！';
				echo json_encode($data1);
			}else{
				$data1['msg'] = $res;
				echo json_encode($data1);
			}
		}
		
		
	}
	
	public function delete(){		
		$post = implode(',',$_POST['id']);	
		$table = M('news');
		$data = $table->delete($post);
		if ($data){
			echo '删除成功！';
		}else {
			echo '删除失败！';
		}
	}
	
	public function ajaxstate(){
		$data = I('get.');
		$table = M('news');
		if ($table->save($data)){
			echo 1;
		}else {
			echo 0;
		}
	}
	
	public function selectajax(){
		$id = I('get.id');
		$table = M('city');
		$data = $table->where("provinceid = $id")->select();
		$res['str'] = "<option value='0'>请选择在市级单位</option>";
		$res['str1'] = "<li class='sel' onclick='sel(this)'>请选择在市级单位</li>";
		foreach ($data as $val){
			$res['str'].="<option value='".$val['cityid']."'>".$val['city']."</option>";
		}
		foreach ($data as $val){
			$res['str1'].="<li class='sel' onclick='sel(this)'>".$val['city']."</li>";
		}
		echo json_encode($res);
	}
	
	public function selectajax1(){
		$id = I('get.id');
		$table = M('area');
		$data = $table->where("cityid = $id")->select();
		$res['str'] = "<option value='0'>请选择在区县单位</option>";
		$res['str1'] = "<li class='sel' onclick='sel(this)'>请选择在区县单位</li>";
		foreach ($data as $val){
			$res['str'].="<option value='".$val['areaid']."'>".$val['area']."</option>";
		}
		foreach ($data as $val){
			$res['str1'].="<li class='sel' onclick='sel(this)'>".$val['area']."</li>";
		}
		echo json_encode($res);
	}
	
	//二级分类
	public function selectajax3(){
		$id = I('get.id');
		$table = M('group');
		$data = $table->where("pid = $id")->order("ord asc,id asc")->select();
		$res['str'] = "<option value='0'>请选择二级分类</option>";
		$res['str1'] = "<li class='sel' onclick='sel(this)'>请选择二级分类</li>";
		foreach ($data as $val){
			$res['str'].="<option value='".$val['id']."'>".$val['title']."</option>";
		}
		foreach ($data as $val){
			$res['str1'].="<li class='sel' onclick='sel(this)'>".$val['title']."</li>";
		}
		echo json_encode($res);
	}
	
	//律师
	public function selectajax5(){
		$id = I('get.id');
		$table = M('lawyer');
		if ($id){
			$data = $table->where("lawid = $id")->select();
		}else {
			$data = 0;
		}
	
		if (!$data){
			echo 0;
			exit;
		}
		foreach ($data as $val){
			$res['str'].="<label style='display: none;'><input type='checkbox' Value='".$val['id']."' name='lawyer[]' />".$val['title']."</label>";
		}
		foreach ($data as $val){
			$res['str1'].="<a onclick='checkb(this)'>".$val['title']."</a>";
		}
		echo json_encode($res);
	}
	
	
	
	public function ajax(){
		if (!empty($_POST['param'])){
			$table = M('news');
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