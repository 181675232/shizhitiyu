<?php
namespace Home\Controller;
use Think\Controller;

class CommonController extends Controller {
	
	public function header(){
		$group = M('group');
		$law = M('law');
		$base = M('base');
		$advert = M('advert');
		$data = $group->where("pid=0 and id!=5")->order('ord asc')->select();
		foreach ($data as $key=>$val){
			$data[$key]["tag"] = $group->where("pid='{$val['id']}' and isred=2")->order("ord asc,id asc")->select(); 
		}
		$lawdata = $law->where("isred=2")->order("ord asc,id asc")->select();
		$bases = $base->field("title as base_title,keyword as base_keyword,description as base_description,tel as base_tel,content as base_content")->find(1);
		
		$adverts = $advert->order("id asc")->select();
		$this->assign('law',$lawdata);
		$this->assign('data',$data);
		$this->assign($bases);
		$this->assign('advert',$adverts);
	}
	public function footer(){
 			$about = M("about");
			$abouts = $about->select();
			$this->assign('about',$abouts);
	}
	
	public function news(){
		if(I('get.id')){
 			$id = I('get.id');
 			$news = M('news');
 			$lawyer = M('lawyer');
 			$comment = M('comment');
 			$clollection = M('clollection');
			$data = $news->find($id);
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
			
			$where['groupid'] = $data['groupid'];
			$where['state'] = 2;
			//评论前5条
			$data_comment = $news->where($where)->field("id,title")->select();
			foreach ($data_comment as $key=>$val){
				$data_comment[$key]['comment_count'] = $comment->where("newid='{$val['id']}' and  state=2")->count();
			}
			//数组排序
			foreach ($data_comment as $val){
				$comment_count[] = $val['comment_count'];
			}
			array_multisort($comment_count, SORT_DESC, $data_comment);
			$data_comment = array_slice($data_comment, 0, 5);
			 
			//转发前5条
			$data_share = $news->where($where)->order("share desc")->field("id,title,share")->limit(5)->select();
			//点赞前5条
			$data_upper = $news->where($where)->order("upper desc")->field("id,title,upper")->limit(5)->select();
			//收藏前5条
			$data_clollection = $news->where($where)->order("clollection desc")->field("id,title,clollection")->limit(5)->select();
			$this->assign($data);
			$this->assign('comment_data',$data_comment);  //评论前5条
			$this->assign('share_data',$data_share);  //转发前5条
			$this->assign('upper_data',$data_upper);  //点赞前5条
			$this->assign('clollection_data',$data_clollection);  //收藏前5条
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
			$res = $table->where("id = $id")->setInc('clollection'); // 收藏加1;
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

	//新闻分享
	function share(){
		$id = I('post.id');
		$table = M("news");
		if($id){
			$res = $table->where("id = $id")->setInc('share'); // 点赞加1;
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