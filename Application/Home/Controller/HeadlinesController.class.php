<?php
namespace Home\Controller;
class HeadlinesController extends CommonController {
	
    public function index(){
	     $news = M('news');
	     $group = M('group');
	     $comment = M('comment');
	     $clollection = M('clollection');
	     
	     $data = $group->find(1);
	     
	     $istop = $news
	     ->join("left join t_group on t_news.tag = t_group.id")
	     ->where("t_news.groupid =1")->limit("0,3")
	     ->field("t_news.*,t_group.title as tag_title,t_group.title_short")
	     ->order('t_news.istop desc,t_news.ord asc,t_news.id desc')->select();
	     foreach ($istop as $key=>$val){
	     	$istop[$key]['count'] =   $comment->where("newid='{$val['id']}' and  state=2")->count();
	     	$coll = $clollection->where("pid='{$val['id']}' and userid='{$_SESSION['user_id']}'")->find();//是否收藏
	     	if($coll){
	     		$istop[$key]['is_coll'] = 1;
	     	}else{
	     		$istop[$key]['is_coll'] = 0;
	     	}
	     }
	     $isred = $group->where("pid=1")->order('ord asc')->select();
	     
	   	if (I('get.tag')){
	      $where['t_news.tag'] = I('get.tag');
	      $page = 0;
	     }else{
	     	$page = 3;
	     }
	     $where1['groupid'] = $where['t_news.groupid'] = 1;
	     $res = $news
	     ->join("left join t_group on t_news.tag = t_group.id")
	     ->where($where)->order('t_news.istop desc,t_news.ord asc,t_news.id desc')->limit("$page,10")
	     ->field("t_news.*,t_group.title as tag_title,t_group.title_short")
	     ->select();
	     foreach ($res as $key=>$val){
	     	$res[$key]['count'] =   $comment->where("newid='{$val['id']}' and  state=2")->count();
	     	$coll = $clollection->where("pid='{$val['id']}' and userid='{$_SESSION['user_id']}'")->find();//是否收藏
	     	if($coll){
	     		$res[$key]['is_coll'] = 1;
	     	}else{
	     		$res[$key]['is_coll'] = 0;
	     	}
	     }
	     
	     //评论前5条
	     $res_comment = $news->where($where1)->field("id,title")->select();
	    foreach ($res_comment as $key=>$val){
	    	$res_comment[$key]['comment_count'] = $comment->where("newid='{$val['id']}' and  state=2")->count();
	    }
	    
	    //数组排序 
	    foreach ($res_comment as $val){
	    	$comment_count[] = $val['comment_count'];
	    }
	    array_multisort($comment_count, SORT_DESC, $res_comment);
	    $res_comment = array_slice($res_comment, 0, 5);
	  
	    //转发前5条
	    $res_share = $news->where($where1)->order("share desc")->field("id,title,share")->limit(5)->select();
	    //点赞前5条
	    $res_upper = $news->where($where1)->order("upper desc")->field("id,title,upper")->limit(5)->select();
	    //收藏前5条
	    $res_clollection = $news->where($where1)->order("clollection desc")->field("id,title,clollection")->limit(5)->select();
	     $this->assign('istop',$istop);//置顶3条
	     $this->assign('isred',$isred);//二级分类
	     $this->assign('news',$res);//新闻数据
	     $this->assign('comment_data',$res_comment);  //评论前5条
	     $this->assign('share_data',$res_share);  //转发前5条
	     $this->assign('upper_data',$res_upper);  //点赞前5条
	     $this->assign('clollection_data',$res_clollection);  //收藏前5条
	     $this->assign('group_data',$data);  
	     $this->header();
	     $this->footer();
	     $this->display();
    }
    
/* 	//获取下一栏数据
	public function newsMore(){
		$Action = new \Org\Util\More();
		$page = $Action->_get('page');
		$tag = $Action->_get('tag');
		$groupid = $Action->_get('groupid');
		if($tag!="0"){
		 	$where['tag'] = intval($tag);
		 	$pages = $page*10;
		}else{
		 	$where['groupid'] = intval($groupid);
		 	$pages = $page*10+3;
		}
        $list = M('news')->where($where)->order('ord asc,addtime desc')->limit("$pages,10")->select();
        if($list){
      		  $Action->ajaxReturn($list);
        }else{
      	 	 echo 	1;
        }
	} */
    
    
    
}