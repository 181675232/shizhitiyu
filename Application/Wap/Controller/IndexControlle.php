<?php
namespace Wap\Controller;
class IndexController extends CommonController {
	
    public function index(){
    	$news = M('news');
    	$comment = M('comment');
    	$clollection = M('clollection');
    	/* $where['t_news.type'] = array('neq','3'); */
		$where['t_news.groupid'] = array(array('neq','4'),array('neq','5'));
    	$data = $news
    	->join("left join t_group on t_news.tag = t_group.id")
    	->where($where)->order("t_news.ord asc,t_news.id desc")
    	->field("t_news.*,t_group.title as tag_title")
    	->select();
    	
    	
    	$time = new \Org\Util\Date();
    	foreach ($data as $key=>$val){
    		$data[$key]['time'] = $time->timeDiff(intval($val['addtime']));
    		
    		$data[$key]['count'] =   $comment->where("newid='{$val['id']}' and  state=2")->count();
    		$coll = $clollection->where("pid='{$val['id']}' and userid='{$_SESSION['user_id']}'")->find();//是否收藏
    		if($coll){
    			$data[$key]['is_coll'] = 1;
    		}else{
    			$data[$key]['is_coll'] = 0;
    		}
    	}
    	//print_r($data);
    	$this->assign("news",$data);
		$this->header();
		$this->footer();
	    $this->display();
    }
    
    
}