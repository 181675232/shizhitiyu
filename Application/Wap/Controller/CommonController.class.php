<?php
namespace Wap\Controller;
use Think\Controller;

class CommonController extends Controller {

    public $type_id = '1';

	public function header(){

	    $type = M('type');
	    $type_data = $type->find($this->type_id);
		$this->assign("type_data");
	}
	public function footer(){
 /*			$about = M("about");
			$abouts = $about->select();
			$this->assign('about',$abouts);*/
	}
	
	public function news(){
		if(I('get.id')){
 			$id = I('get.id');
 			$news = M('news');
 			$lawyer = M('lawyer');
 			$comment = M('comment');
 			$clollection = M('clollection');
			$data = $news
			->join("left join t_group on t_news.tag = t_group.id")
			->field("t_news.*,t_group.title as tag_title")
			->where("t_news.id = $id")
			->find();
			$time = new \Org\Util\Date();
			$data['time'] = $time->timeDiff(intval($data['addtime']));
			//是否收藏
			$coll = $clollection->where("pid='{$data['id']}' and userid='{$_SESSION['user_id']}'")->find();
			if($coll){
				$data['is_coll'] = 1;
			}else{
				$data['is_coll'] = 0;
			}
			
			$data['count'] = $comment->where("newid = $id and state = 2")->count();
 			if(!$data['count']){
 				$data['count'] = "0";
 			}
			
			if($data['label']){
				$label = explode(",",$data['label']);
				$this->assign("labels",$label);
			}
			$lawyers = $lawyer->field("id,title,img")->select($data['lawyer']);
			$comments = $comment->where("t_comment.newid = $id and t_comment.state = 2 and t_comment.pid=0")
			->join("left join t_user ON t_comment.uid = t_user.id")
			->field("t_comment.*,t_user.username,t_user.simg")
			->order("t_comment.id asc")->select();
			foreach ($comments as $key=>$val){
				$comments[$key]['reply'] = $comment
				->join("left join t_user on t_comment.uid=t_user.id")
				->join("left join t_user as u on t_comment.userid=u.id")
				->where("t_comment.pid = '{$val['id']}' and state = 2")
				->field("t_comment.*,t_user.username,u.username as uname")
				->order("id asc")->select();
				$comments[$key]['count'] = $comment->where("t_comment.pid = '{$val['id']}' and state = 2")->count();
				//$data['count'] = $data['count']+ $comments[$key]['count'];
			}
			
		   //相关文章
	/* 			$where['t_news.groupid'] = array('eq',$data['groupid']);
			$where['t_news.id'] = array('neq',$data['id']);
		$data_relevant = $news->where($where)->order("istop desc,addtime desc")->limit(3)->select(); */
			
			
			//相关文章
	/* 		$where['t_news.groupid'] = array('eq',$data['groupid']); */
			$where['t_news.id'] = array('neq',$data['id']);
			$data_relevant = $news
			->join("left join t_group on t_news.tag = t_group.id")
			->where($where)->order("t_news.istop desc,t_news.id desc")
			->field("t_news.*,t_group.title as tag_title")
			->limit(3)
			->select();
			$time = new \Org\Util\Date();
			foreach ($data_relevant as $key=>$val){
				$data_relevant[$key]['time'] = $time->timeDiff(intval($val['addtime']));
			
				$data_relevant[$key]['count'] =   $comment->where("newid='{$val['id']}' and  state=2")->count();
				$coll = $clollection->where("pid='{$val['id']}' and userid='{$_SESSION['user_id']}'")->find();//是否收藏
				if($coll){
					$data_relevant[$key]['is_coll'] = 1;
				}else{
					$data_relevant[$key]['is_coll'] = 0;
				}
			}
			
			
			
			$this->assign($data);
			$this->assign("data_relevant",$data_relevant);
			$this->assign("lawyers",$lawyers);
			$this->assign("comments",$comments);
		}
		$this->header();
		$this->footer();
		$this->display();
	}
	
	
	//发布评论
	public function comment(){
       if(I('post.description') and I('post.newid')){
			$comment = M('comment');
			if($_SESSION['user_id']){
				$data['uid'] = $_SESSION['user_id']; 
				$data['state'] = 2;
			}
			$data['description'] = I('post.description');
			$data['newid'] = I('post.newid');
			$data['come'] = 2;
			$data['addtime'] = time();
			$res = $comment->add($data);
			if($res){
				if($_SESSION['user_id']){
					echo 2;
				}else{
					echo 4;
				}
			}else{
				echo 3;
			}
		}else{
			echo 1;
		}
		
	}
	
	//发布回复
	public function reply(){
		
		if(I('post.description') and I('post.newid') and I('post.pid')){
			$comment = M('comment');
			if($_SESSION['user_id']){
				$data['uid'] = $_SESSION['user_id'];
				$data['state'] = 2;
			}
			$data['description'] = I('post.description');
			$data['newid'] = I('post.newid');
			$data['pid'] = I('post.pid');
			$data['rid'] = I('post.pid');//评论id 用此id查询@名称
			$data['come'] = 2;
			$data['addtime'] = time();
			
			//获取上级uid
			$userid = $comment->field("uid")->find(I('post.pid'));
			if($userid['uid']){
				$data['userid'] = $userid['uid'];
			}
			
			$res = $comment->add($data);
			if($res){
				if($_SESSION['user_id']){
					echo 2;
				}else{
					echo 4;
				}
			}else{
				echo 3;
			}
		}else{
			echo 1;
		}
	
	}
	
	//二级回复
	public function reply_r(){
	
		if(I('post.description') and I('post.newid') and I('post.pid')){
			$comment = M('comment');
			if($_SESSION['user_id']){
				$data['uid'] = $_SESSION['user_id'];
				$data['state'] = 2;
			}
			$data['description'] = I('post.description');
			$data['newid'] = I('post.newid');
			$data['pid'] = I('post.pid');
			$data['rid'] = I('post.rid');//回复id 用此id查询@名称
			$data['come'] = 2;
			$data['addtime'] = time();
			
			//获取上级uid
			$userid = $comment->field("uid")->find(I('post.rid'));
			if($userid['uid']){
				$data['userid'] = $userid['uid'];
			}
			
			$res = $comment->add($data);
			if($res){
				if($_SESSION['user_id']){
					echo 2;
				}else{
					echo 4;
				}
			}else{
				echo 3;
			}
		}else{
			echo 1;
		}
	
	}
	
	//新闻点赞
	function upper(){
		$type = I('post.type');
		$id = I('post.id');
		$table = M($type);
		if($id and $type){
			$res = $table->where("id = $id")->setInc('upper'); // 点赞加1;
			if($res){
				echo 1;
			}else{
				echo 2;
			}
		}else{
			echo 2;
		}
	}

	//新闻收藏
	function clollection(){
		$id = I('post.id');
		$table = M('news');
		$clollection = M('clollection');
		if(!$_SESSION['user_id']){
			echo 3;
			exit();
		}
		if($id){
			$res = $table->where("id = $id")->setInc('clollection'); // 点赞加1;
			$data['userid'] = $_SESSION['user_id'];
			$data['pid'] = $id;
			$data['addtime'] = time();
			$clollection->add($data);
			if($res){
				echo 1;
			}else{
				echo 2;
			}
		}else{
			echo 2;
		}
	}
	
}