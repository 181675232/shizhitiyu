<?php
namespace Admin\Controller;

class AboutController extends CommonController {
	
	public function index(){
		
		$table = M('About'); // 实例化User对象
		$law = M('law');
		//接收查询数据
		if (I('get.keyword')){
			$keyword = I('get.keyword');
			$data['title'] = array('like',"%{$keyword}%");
		}
		if (I('get.verify')){
			$data['lawid'] = I('get.verify');
		}

		
		
		$count      = $table->where($data)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$res = $table->where($data)	->order('ord asc,addtime asc')->limit($Page->firstRow.','.$Page->listRows)->select();


		$lawdata = $law->order("ord asc,id asc")->select();
		
		$this->assign('law',$lawdata);
		$this->assign('data',$res);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板			
	}
	
	public function add(){

		if (I('post.')){
			$where = array_filter(I('post.'));
			$table = M('About');


			if ($where['content']){
				$where['content'] = stripslashes(htmlspecialchars_decode($_POST['content']));
			}
			$where['addtime'] = time();
			$res =$table->add($where);
			if ($res){
				alertLocation('添加成功！', '/Admin/About');
			}else {
				$this->error('添加失败！');
			}
		}
		$law = M('law');
		$lawdata = $law->order("ord asc,id asc")->select();
		$this->assign('law',$lawdata);
		$this->display();

	}
	
	public function edit(){

		$id = I('get.id');
		if (IS_POST){
			$table = M('About');
			$where = array_filter(I('post.'));
			if ($where['content']){
				$where['content'] = stripslashes(htmlspecialchars_decode($_POST['content']));
			}
			$table->save($where);
			alertBack('修改成功！');			
		}
		$table = M('About');
		$law = M('law');

		$data = $table->where("id = $id")->find();
		$lawdata = $law->order("ord asc,id asc")->select();
		$this->assign('law',$lawdata);
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
		
		$table = M('About');
		if ($table->save($data)){	
	$this->redirect("/Admin/About/index".$str);
		}else {
			$this->error('没有任何修改！');
		}
	}
	
	public function delete(){		
		$post = implode(',',$_POST['id']);	
		$table = M('About');
		$data = $table->delete($post);
		if ($data){
			echo '删除成功！';
		}else {
			echo '删除失败！';
		}
	}
	
	public function ajaxstate(){
		$data = I('get.');
		$table = M('About');
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
	
	public function selectajax3(){
		$id = I('get.id');
		$table = M('law');
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
	
	public function ajax(){
		if (!empty($_POST['param'])){
			$table = M('About');
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