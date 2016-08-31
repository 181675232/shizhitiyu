<?php
namespace Admin\Controller;

class NewsController extends CommonController {
	
	public function index(){
		
		$table = M('news'); // 实例化User对象
		$type = M('type');
		//接收查询数据
		
		if (I('get.keyword')){
			$keyword = I('get.keyword');
			$data['title'] = array('like',"%{$keyword}%");
		}
		if (I('get.verify') and I('get.verify')!==""){
			$data['groupid'] = I('get.verify');
			$this->assign('verify',I('get.verify'));
			$group_tag = $group->where("pid = '{$data['groupid']}'")->order("ord asc,id asc")->select();
			
		}
		if (I('get.tag') and I('get.tag')!=""){
			$data['tag'] = I('get.tag');
			$this->assign('tag',I('get.tag'));
		}
		if (I('get.type')){
			$data['type'] = I('get.type');
			$this->assign('type',I('get.type'));
		}
		$count      = $table->where($data)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$res = $table->where($data)	->order('istop desc,ord asc,addtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		$type_data = $type->order("id asc")->select();
		
		
		$this->assign('type_data',$type_data);
		$this->assign('data',$res);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板			
	}
	
	public function add(){
		if (I('post.')){
			$where = I('post.');
			$table = M('news');
			if ($where['content']){
				$where['content'] = stripslashes(htmlspecialchars_decode($_POST['content']));
			}
			$where['addtime'] = time();
			$where['state'] = 2; 
			$res =$table->add($where);
			if ($res){
				alertLocation('添加成功！', '/Admin/News');
			}else {
				$this->error('添加失败！');
			}
		}
		$type = M('type');
        $type_data = $type->order("id asc")->select();
        $this->assign('type_data',$type_data);
		$this->display();

	}
	
	public function edit(){

		$id = I('get.id');
		if (IS_POST){
			$table = M('news');
			$where =I('post.');
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
		$data = $table->where("id = $id")->find();

        $type = M('type');
        $type_data = $type->order("id asc")->select();
        $this->assign('type_data',$type_data);
		$this->assign($data);
		$this->display();
	}
	
	public function state(){
		$data = I('get.');		

		$str ='/';
		if (I('get.p')){
			$str.= 'p/'.I('get.p').'/';
		}
		if (I('get.verify')){
			$str.= 'verify/'.I('get.verify').'/';
		}
		if (I('get.keyword')){
			$str.= 'keyword/'.I('get.keyword').'/';
		}
		if (I('get.tag')){
			$str.= 'tag/'.I('get.tag').'/';
		}
		if (I('get.type')){
			$str.= 'type/'.I('get.type').'/';
		}
		$table = M('news');
		if ($table->save($data)){	
	$this->redirect("/Admin/News/index".$str);
		}else {
			$this->error('没有任何修改！');
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