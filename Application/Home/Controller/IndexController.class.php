<?php
namespace Home\Controller;
use Think\Controller\RpcController;
class IndexController extends CommonController {
	
    public function index(){
    	
    	$news = M('news');
    	$link = M('link');
    	$comment = M('comment');
    	$clollection = M('clollection');
    	
    	//磅礴头条
    	$data_headlines = $news
    	->join("left join t_group on t_news.tag = t_group.id")
    	->where("t_news.groupid=1")->order("t_news.ord asc,t_news.id desc")
    	->field("t_news.*,t_group.title as tag_title,t_group.title_short")
    	->limit(8)
    	->select();
    	foreach ($data_headlines as $key=>$val){
    		$data_headlines[$key]['count'] =   $comment->where("newid='{$val['id']}' and  state=2")->count();
    		$coll = $clollection->where("pid='{$val['id']}' and userid='{$_SESSION['user_id']}'")->find();//是否收藏
    		if($coll){
    			$data_headlines[$key]['is_coll'] = 1;
    		}else{
    			$data_headlines[$key]['is_coll'] = 0;
    		}
    	}
    	//激情创意
    	$data_creative = $news
    	->join("left join t_group on t_news.tag = t_group.id")
    	->where("t_news.groupid=2")->order("t_news.ord asc,t_news.id desc")
    	->field("t_news.*,t_group.title as tag_title,t_group.title_short")
    	->limit(9)
    	->select();
    	foreach ($data_creative as $key=>$val){
    		$data_creative[$key]['count'] =   $comment->where("newid='{$val['id']}' and  state=2")->count();
    		$coll = $clollection->where("pid='{$val['id']}' and userid='{$_SESSION['user_id']}'")->find();//是否收藏
    		if($coll){
    			$data_creative[$key]['is_coll'] = 1;
    		}else{
    			$data_creative[$key]['is_coll'] = 0;
    		}
    	}
    	//文都情怀
    	$data_feelings = $news
    	->join("left join t_group on t_news.tag = t_group.id")
    	->where("t_news.groupid=3")->order("t_news.ord asc,t_news.id desc")
    	->field("t_news.*,t_group.title as tag_title,t_group.title_short")
    	->limit(9)
    	->select();
    	foreach ($data_feelings as $key=>$val){
    		$data_feelings[$key]['count'] =   $comment->where("newid='{$val['id']}' and  state=2")->count();
    		$coll = $clollection->where("pid='{$val['id']}' and userid='{$_SESSION['user_id']}'")->find();//是否收藏
    		if($coll){
    			$data_feelings[$key]['is_coll'] = 1;
    		}else{
    			$data_feelings[$key]['is_coll'] = 0;
    		}
    	}
    	
    	
    	
    	$slide = $news
    	->join("left join t_group on t_news.tag = t_group.id")
    	->where("t_news.type=2")->order("t_news.ord asc,t_news.id desc")
    	->field("t_news.*,t_group.title as tag_title,t_group.title_short")
    	->select();
    	 foreach ($slide as $key=>$val){
	     	$slide[$key]['count'] =   $comment->where("newid='{$val['id']}' and  state=2")->count();
	     	$coll = $clollection->where("pid='{$val['id']}' and userid='{$_SESSION['user_id']}'")->find();//是否收藏
	     	if($coll){
	     		$slide[$key]['is_coll'] = 1;
	     	}else{
	     		$slide[$key]['is_coll'] = 0;
	     	}
	     }
    	$video = $news->where("type=3")->order("ord asc,id desc")->select();
    	$links = $link->order("ord asc,id asc")->select();
    	
    	$this->assign("data_headlines",$data_headlines);
    	$this->assign("data_creative",$data_creative);
    	$this->assign("data_feelings",$data_feelings);
    	$this->assign("slide",$slide);
    	$this->assign("video",$video);
    	$this->assign("link",$links);
		$this->header();
		$this->footer();
	    $this->display();
    }
    
    
}