<?php
namespace Home\Controller;
class MemberController extends CommonController {
	
	public function _initialize(){
		header("Content-Type: text/html; charset=UTF-8");
	    	if($_SESSION['user_id']==""){
    		alertLocation("请登录","/index/");
    	}
	}

	public function memright(){
		$user = M('user');
		$news = M('news');
		$comment = M('comment');
		$res= $user->field("simg")->find($_SESSION['user_id']);
		$res['n_conut'] = $news->where("userid='{$_SESSION['user_id']}' and state=2")->count();
		$res['c_conut'] = $comment->where("uid='{$_SESSION['user_id']}'")->count();//去重'DISTINCT newid'
		$this->assign('memright',$res);
	}
	
	
	//交易记录
    public function index(){
    	//交易记录
    	$order = M('order');
    	$where['t_order.uid'] = $_SESSION['user_id'];
    	$res = $order
    	->join("left join t_lawyer ON t_order.lawid=t_lawyer.id")->field("t_order.*,t_lawyer.tag,t_lawyer.phone,t_lawyer.img")
    	->where($where)->order("id desc")->limit(6)->select();
    	$this->assign('order',$res);
	     $this->header();
	     $this->memright();
	     $this->footer();
	     $this->display();
    }
    
    //交易记录 ajax 加载
    public function order_ajax(){
    	$order = M('order');
    	if(I('get.page')){
    		$Action = new \Org\Util\More();
    		$page = $Action->_get('page');
    		$pages = $page*6;
    		$where['t_order.uid'] = $_SESSION['user_id'];
    		$list = M('order')
    		->join("left join t_lawyer ON t_order.lawid=t_lawyer.id")->field("t_order.*,t_lawyer.tag,t_lawyer.phone,t_lawyer.img")
    		->where($where)->order('t_order.id desc')->limit("$pages,6")->select();
    		foreach ($list as $key=>$val){
    			$list[$key]['addtime'] = date("Y-m-d",$val['addtime']);
    		}
    		//	print_r($list);
    		if($list){
    			$Action->ajaxReturn($list);
    		}else{
    			echo 	1;
    		}
    	}
    }
    
    public function collection(){
    	//我的收藏
    	$Collection = M('clollection');
    	$where['t_clollection.userid'] = $_SESSION['user_id'];
    	$res = $Collection
    	->join("left join t_news ON t_clollection.pid=t_news.id")
    	->order('t_clollection.id desc')
    	->where($where)->limit(6)->field("t_clollection.*,t_news.title,t_news.simg,t_news.id as newid")->select();
    	$this->assign('collection',$res);
    	$this->header();
    	$this->memright();
    	$this->footer();
    	$this->display();
    }
    
    //我的收藏 ajax 加载
    public function collection_ajax(){
    	if(I('get.page')){
    		$Action = new \Org\Util\More();
    		$page = $Action->_get('page');
    		$pages = $page*6;
    		$where['t_clollection.userid'] = $_SESSION['user_id'];
    		$list = M('clollection')
	    	->join("left join t_news ON t_clollection.pid=t_news.id")
	    	->order('t_clollection.id desc')
	    	->where($where)->limit("$pages,6")->field("t_clollection.*,t_news.title,t_news.simg,t_news.id as newid")->select();
    		foreach ($list as $key=>$val){
    			$list[$key]['addtime'] = date("Y-m-d",$val['addtime']);
    		}
    		//	print_r($list);
    		if($list){
    			$Action->ajaxReturn($list);
    		}else{
    			echo 	1;
    		}
    	}
    }
    //我的评论
    public function comment(){
    	$comment = M('comment');
     	$where['t_comment.uid'] = $_SESSION['user_id'];
    	$res = $comment
    	->join("left join t_news ON t_comment.newid=t_news.id")
    	->order('t_comment.id desc')
    	->where($where)->limit(6)->field("t_comment.*,t_news.title,t_news.simg,t_news.id as newid")->select();
    	$this->assign('comment',$res);
    	$this->header();
    	$this->memright();
    	$this->footer();
    	$this->display();
    }
    
    //我的评论ajax 加载
    public function comment_ajax(){
    	if(I('get.page')){
    		$Action = new \Org\Util\More();
    		$page = $Action->_get('page');
    		$pages = $page*6;
    		$where['t_comment.uid'] = $_SESSION['user_id'];
    		$list = M('comment')
    		->join("left join t_news ON t_comment.newid=t_news.id")
    		->order('t_comment.id desc')
    		->where($where)->limit("$pages,6")->field("t_comment.*,t_news.title,t_news.simg,t_news.id as newid")->select();
    		foreach ($list as $key=>$val){
    			$list[$key]['addtime'] = date("Y-m-d",$val['addtime']);
    		}
    		//	print_r($list);
    		if($list){
    			$Action->ajaxReturn($list);
    		}else{
    			echo 	1;
    		}
    	}
    }
    
    //我的稿件
    public function manuscript(){
    	if(I('post.')){
    		$news = M('news');
    		$data = I('post.');
			
    		if($data['submit']=="发送稿件"){
    			$where['state'] = 1;
    		}elseif($data['submit']=="保存草稿"){
    			$where['state'] = 4;
    		}else{
    			alertBack("数据出错！");
    		}
    		
    		if($data['content']==""){
    			alertBack("请填写内容！");
    		}
    		if($_SESSION['user_id']==""){
    			alertBack("请登陆！");
    		}
    		
    		$where['userid'] = $_SESSION['user_id'];
    		$where['title'] = $data['title'];
    		$where['tag'] = $data['tag'];
    		$where['content'] = $data['content'];
    		$where['message'] = $data['message'];
    		$where['publish_type'] = $data['publish_type'];
    		$where['groupid'] = 4;
    		$where['type'] = 1;
    		$where['addtime'] = time();
    		$res1 = $news->add($where);
    	    if($res1){
    	    	if($data['submit']=="发送稿件"){
    	    		alertReplace("发布成功");
    	    	}elseif($data['submit']=="保存草稿"){
    	    		alertReplace("保存成功");
    	    	}
    		}else{
    		    if($data['submit']=="发送稿件"){
    	    		alertReplace("发布失败");
    	    	}elseif($data['submit']=="保存草稿"){
    	    		alertReplace("保存失败");
    	    	}
    		}
    	}
    	$group = M('group');
    	$res= $group->where("pid=4")->order("ord asc,id asc")->select();
    	$this->assign('select','1');
    	$this->assign('group',$res);
    	$this->header();
    	$this->memright();
    	$this->footer();
    	$this->display();
    }
    
    //已发表
    public function publish(){
    	$news = M('news');
    	$where['userid'] = $_SESSION['user_id'];
    	$where['state'] = 2;
    	$res = $news->where($where)->limit(6)->order("id desc")->select();
    	$this->assign('select','2');
    	$this->assign('publish',$res);
    	$this->header();
    	$this->memright();
    	$this->footer();
    	$this->display();
    }
    
    //已发表ajax加载
    public function publish_ajax(){
    	if(I('get.page')){
    		$Action = new \Org\Util\More();
    		$page = $Action->_get('page');
    		$pages = $page*6;
    		$where['userid'] = $_SESSION['user_id'];
    		$where['state'] = 2;
    		$list = M('news')
    		->order('id desc')
    		->where($where)->limit("$pages,6")->field("t_news.id,t_news.title,t_news.content,t_news.simg,t_news.addtime")->select();
    		foreach ($list as $key=>$val){
    			$list[$key]['addtime'] = date("Y-m-d H:i:s",$val['addtime']);
    		}
    		//	print_r($list);
    		if($list){
    			//print_r($list);
    			$Action->ajaxReturn($list);
    		}else{
    			echo 	1;
    		}
    	}
    }
    
    //审核中
    public function review(){
    	$news = M('news');
    	$where['userid'] = $_SESSION['user_id'];
    	$where['state'] = 1;
    	$res = $news->where($where)->limit(6)->order("id desc")->select();
    	$this->assign('select','3');
    	$this->assign('review',$res);
    	$this->header();
    	$this->memright();
    	$this->footer();
    	$this->display();
    }
    
    //审核中ajax加载
    public function review_ajax(){
    	if(I('get.page')){
    		$Action = new \Org\Util\More();
    		$page = $Action->_get('page');
    		$pages = $page*6;
    		$where['userid'] = $_SESSION['user_id'];
    		$where['state'] = 1;
    		$list = M('news')
    		->order('id desc')
    		->where($where)->limit("$pages,6")->field("t_news.id,t_news.title,t_news.content,t_news.simg,t_news.addtime")->select();
    		foreach ($list as $key=>$val){
    			$list[$key]['addtime'] = date("Y-m-d H:i:s",$val['addtime']);
    		}
    		//	print_r($list);
    		if($list){
    			//print_r($list);
    			$Action->ajaxReturn($list);
    		}else{
    			echo 	1;
    		}
    	}
    }
    
    //退稿
    public function rejection(){
    	$news = M('news');
    	$where['userid'] = $_SESSION['user_id'];
    	$where['state'] = 3;
    	$res = $news->where($where)->limit(6)->order("id desc")->select();
    	
    	foreach ($res as $key=>$val){
    		$res[$key]['reason'] = explode(',', $val['reason']);
    	}
    	$this->assign('select','4');
    	$this->assign('rejection',$res);
    	$this->header();
    	$this->memright();
    	$this->footer();
    	$this->display();
    }
    
    //退稿ajax加载
    public function rejection_ajax(){
    	if(I('get.page')){
    		$Action = new \Org\Util\More();
    		$page = $Action->_get('page');
    		$pages = $page*6;
    		$where['userid'] = $_SESSION['user_id'];
    		$where['state'] = 3;
    		$list = M('news')
    		->order('id desc')
    		->where($where)->limit("$pages,6")->field("t_news.id,t_news.title,t_news.content,t_news.simg,t_news.addtime,t_news.reason")->select();
    		foreach ($list as $key=>$val){
    			$list[$key]['addtime'] = date("Y-m-d H:i:s",$val['addtime']);
    			$list[$key]['reason'] = explode(',', $val['reason']);
    		}
    		//	print_r($list);
    		if($list){
    			//print_r($list);
    			$Action->ajaxReturn($list);
    		}else{
    			echo 	1;
    		}
    	}
    }
    
    //草稿箱
    public function draft(){
    	$news = M('news');
    	$where['userid'] = $_SESSION['user_id'];
    	$where['state'] = 4;
    	$res = $news->where($where)->limit(6)->order("id desc")->select();
    	$this->assign('select','5');
    	$this->assign('draft',$res);
    	$this->header();
    	$this->memright();
    	$this->footer();
    	$this->display();
    }
    
    //草稿箱ajax加载
    public function draft_ajax(){
    	if(I('get.page')){
    		$Action = new \Org\Util\More();
    		$page = $Action->_get('page');
    		$pages = $page*6;
    		$where['userid'] = $_SESSION['user_id'];
    		$where['state'] = 4;
    		$list = M('news')
    		->order('id desc')
    		->where($where)->limit("$pages,6")->field("t_news.id,t_news.title,t_news.content,t_news.simg,t_news.addtime")->select();
    		foreach ($list as $key=>$val){
    			$list[$key]['addtime'] = date("Y-m-d H:i:s",$val['addtime']);
    		}
    		//	print_r($list);
    		if($list){
    			//print_r($list);
    			$Action->ajaxReturn($list);
    		}else{
    			echo 	1;
    		}
    	}
    }
    
    
    //文章修改
    public function article(){
    	$news = M('news');
    	$id = I('get.id');
    	if(I('post.')){
    		$data = I('post.');
    			
    		if($data['submit']=="发送稿件"){
    			$where['state'] = 1;
    		}elseif($data['submit']=="保存草稿"){
    			$where['state'] = 4;
    		}else{
    			alertBack("数据出错！");
    		}
    	
    		if($data['content']==""){
    			alertBack("请填写内容！");
    		}	
    		if($data['id']!=$id){
    			alertBack("数据出错！");
    		}
    		$where['id'] = $id;
    		$where['userid'] = $_SESSION['user_id'];
    		$where['title'] = $data['title'];
    		$where['tag'] = $data['tag'];
    		$where['content'] = $data['content'];
    		$where['message'] = $data['message'];
    		$where['publish_type'] = $data['publish_type'];
    		$where['groupid'] = 4;
    		$where['type'] = 1;
    		$where['addtime'] = time();
    		$res1 = $news->save($where);
    		if($res1){
    			if($data['submit']=="发送稿件"){
    				alertReplace("发布成功");
    			}elseif($data['submit']=="保存草稿"){
    				alertReplace("保存成功");
    			}
    		}else{
    			if($data['submit']=="发送稿件"){
    				alertReplace("发布失败");
    			}elseif($data['submit']=="保存草稿"){
    				alertReplace("保存失败");
    			}
    		}
    	}
    	$group = M('group');
    	$res= $group->where("pid=4")->order("ord asc,id asc")->select();
    	
    	$where1['id'] = I('get.id');
    	$where['userid'] = $_SESSION['user_id'];
    	$where1['state'] = array('neq',2);
    	$res1 = $news->where($where1)->find();
    	if(!$res1){
    		alertBack("数据出错！");
    	}
    	$this->assign('article',$res1);
    	$this->assign('select','6');
    	$this->assign('group',$res);
    	$this->header();
    	$this->memright();
    	$this->footer();
    	$this->display();
    	
    }
    
    //账户设置
    public function account(){
    	$user = M('user');
    	$res = $user->find($_SESSION['user_id']);
    	$this->assign($res);
    	$this->header();
    	$this->memright();
    	$this->footer();
    	$this->display();
    }
    
    //修改头像
    public function portrait(){
    	$user = M('user');
    	if(I('post.')){
    		$data = I('post.');
    		$where['id'] = $_SESSION['user_id'];
    		$where['simg'] = $data['simg'];
    		$res = $user->save($where);
    	if($res){
				alertReplace("修改成功");
			}else{
				alertBack("修改失败");
			}
    	}
    }
    //修改简介
    public function introduction(){
        	$user = M('user');
    	if(I('post.')){
    		$data = I('post.');
    		$where['id'] = $_SESSION['user_id'];
    		$where['introduction'] = $data['introduction'];
    		$res = $user->save($where);
    	if($res){
				alertReplace("修改成功");
			}else{
				alertBack("修改失败");
			}
    	}
    }
    //修改密码
    public function pass(){
    	$user = M('user');
    	if(I('post.')){
    		$data = I('post.');
    		$res1 = $user->find($_SESSION['user_id']);
    		if(md5($data['password'])!=$res1['password']){
    			alertBack("旧密码错误");
    		}

    		if($data['pass']!=$data['pass1']){
    			alertBack("两次输入密码不一致");
    		}
    		
    		$where['id'] = $_SESSION['user_id'];
    		$where['password'] = md5($data['pass']);
    		$res = $user->save($where);
    		if($res){
    			alertReplace("修改成功");
    		}else{
    			alertBack("修改失败");
    		}
    	}
    }  
    
    //vip
    public function vip(){
    	$user = M('user');
    	$res= $user->field("viptime")->find($_SESSION['user_id']);
		if($res['viptime']<time()){
			$res['viptime'] = 0;
		}else{
			$res['viptime'] = round(($res['viptime']-time())/3600/24) ;
		}
    	$this->assign($res);
    	$this->header();
    	$this->memright();
    	$this->footer();
    	$this->display();
    }
    
    //vip
    public function buyvip(){
    	if(I('post.')){
    		$order = M('order');
    		$user = M('user');
    		$res1= $user->find($_SESSION['user_id']);
    	
    		$where['order']=time().rand(100000,999999);//订单编号
    		$where['type'] = 2;
    		$where['uid'] = $_SESSION['user_id'];
    		$where['state'] = 1;
    		$where['title'] = "购买会员";
    		$where['addtime'] = time();
    		if($res1['type']==1){
    			$where['price'] = 60;
    			$where['description'] = "个人用户";
    		}else if($res1['type']==2){
    			$where['price'] = 120;
    			$where['description'] = "企业用户";
    		}else{
    			alertBack("用户类型出错，请联系客服！");
    		}
    		$res= $order->add($where);
    		if($res){
    			alertReplace("订单提交成功");
    		}else{
    			alertReplace("订单提交失败");
    		}
    	}
    	
    	$this->header();
    	$this->memright();
    	$this->footer();
    	$this->display();
    }
   
	public function  pay(){    
		$order = M('order');
		if(I('get.id')){
			$where['id'] = I('get.id');
			$where['state'] = 1;
			$where['uid'] = $_SESSION['user_id'];
			$data = $order->where($where)->find();
			if(!$data){
				alertBack("数据出错！");
			}
		}else{
			alertBack("数据出错！");
		}
		
		if(I('post.')){
			$where1 = I('post.');
				if($where1['id']!=I('get.id')){
					alertBack("数据出错！");
				}
				$where2['id'] = $where1['id'];
				$where2['state'] = 1;
				$where2['uid'] = $_SESSION['user_id'];
				$res = $order->where($where2)->find();
				if(!$res){
					alertBack("数据出错！");
				}
				$data1['orderid'] = $res['order'];
				$data1['subject'] = $res['title'];
				$data1['price'] = $res['price'];
				$data1['message'] = $res['description'];
				if($where1['pay_type']==1){
					$data1['notify_url'] = '101.200.81.192:8084';
					$data1['return_url'] = '101.200.81.192:8084';
					pay($data1);
				}else{
					alertBack("数据出错！");
				}

		}
		
		
		$this->assign($data);
		$this->header();
		$this->footer();
		$this->display();
	}
    
    //公共删除
    public function mdelete(){
    	if(I('post.')){
    		$data = I('post.');
    		$table = M($data['table']);
    		if($data['table']=='comment'){
    			$where['uid'] = $_SESSION['user_id'];
    		}else{
    			$where['userid'] = $_SESSION['user_id'];
    		}
    		$res = $table->where($where)->delete($data['id']);
    		if($res){
    			echo 1;
    		}else{
    			echo "数据出错";
    		}
    	}else{
    		echo "数据出错";
    	}
    }
}