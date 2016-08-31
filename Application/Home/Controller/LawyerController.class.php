<?php
namespace Home\Controller;
class LawyerController extends CommonController {
	
    public function index(){
	     $law= M("law");
	     $group = M('group');
	     $news= M("news");
	     $comment = M('comment');
	     $clollection = M('clollection');
	     $group_data = $group->find(5);
	     $res = $law->order('ord asc,id asc')->select();
	     
	     $res1 = $group->where("pid=5")->order("ord asc")->select();
		foreach ($res1 as $key=>$val){
			$res1[$key]['data'] = $news->where("tag='{$val['id']}'")->Order("ord asc,id desc")->select();
		}	
		
		foreach ($res1 as $key=>$val){
			foreach ($val['data'] as $k=>$v){
				$res1[$key]['data'][$k]['count'] =$comment->where("newid='{$v['id']}' and  state=2")->count();
				$coll = $clollection->where("pid='{$v['id']}' and userid='{$_SESSION['user_id']}'")->find();//是否收藏
				if($coll){
					$res1[$key]['data'][$k]['is_coll'] = 1;
				}else{
					$res1[$key]['data'][$k]['is_coll'] = 0;
				}
			}
		}
		
	     $this->assign('laws',$res);
	     $this->assign('group_data',$group_data);
	     $this->assign('law_data',$res1);
	     
	     $this->header();
	     $this->footer();
	     $this->display();
    }
    
    public function law(){
    	$law= M("law");
    	$lawyer= M("lawyer");
    	$group = M('group');
    	$news= M("news");
    	$comment = M('comment');
    	$clollection = M('clollection');
    	$res = $law->order('ord asc,id asc')->select();
    	$group_data = $group->find(5);
    	
    	$res1 = $group->where("pid=5")->order("ord asc")->select();
    	foreach ($res1 as $key=>$val){
    		$res1[$key]['data'] = $news->where("tag='{$val['id']}'")->Order("ord asc,id desc")->select();
    	}
    	
    	foreach ($res1 as $key=>$val){
    		foreach ($val['data'] as $k=>$v){
    			$res1[$key]['data'][$k]['count'] =$comment->where("newid='{$v['id']}' and  state=2")->count();
    			$coll = $clollection->where("pid='{$v['id']}' and userid='{$_SESSION['user_id']}'")->find();//是否收藏
    			if($coll){
    				$res1[$key]['data'][$k]['is_coll'] = 1;
    			}else{
    				$res1[$key]['data'][$k]['is_coll'] = 0;
    			}
    		}
    	}
    	
    	if(I('get.tag')){
    		$where['lawid'] = I('get.tag');
    		$data = $lawyer->where($where)->select(); 
    	}else{
    		$this->redirect("/Lawyer");
    	}
    	$this->assign('laws',$res);
    	$this->assign('lawyer',$data);
    	$this->assign('group_data',$group_data);
    	$this->assign('law_data',$res1);
    	$this->header();
    	$this->footer();
    	$this->display();
    }
    
    public function lawyer(){
    	$law= M("law");
    	$lawyer = M('lawyer');
    	$group = M('group');
    	$news= M("news");
    	$comment = M('comment');
    	$clollection = M('clollection');
    	$data = $lawyer->find(I('get.id'));
    	$tag = explode(",",$data['tag']);
    	$res = $law->order('ord asc,id asc')->select();	
    	$where['lawid'] = $data['lawid'];
    	$where['id'] = array("neq",$data['id']);
    	$data1= $lawyer->where($where)->order("ord asc,id asc")->limit(6)->select();
    	
    	$group_data = $group->find(5);
    	
    	$res1 = $group->where("pid=5")->order("ord asc")->select();
    	foreach ($res1 as $key=>$val){
    		$res1[$key]['data'] = $news->where("tag='{$val['id']}'")->Order("ord asc,id desc")->select();
    	}
    	 
    	foreach ($res1 as $key=>$val){
    		foreach ($val['data'] as $k=>$v){
    			$res1[$key]['data'][$k]['count'] =$comment->where("newid='{$v['id']}' and  state=2")->count();
    			$coll = $clollection->where("pid='{$v['id']}' and userid='{$_SESSION['user_id']}'")->find();//是否收藏
    			if($coll){
    				$res1[$key]['data'][$k]['is_coll'] = 1;
    			}else{
    				$res1[$key]['data'][$k]['is_coll'] = 0;
    			}
    		}
    	}
    	
    	$this->assign('laws',$res);
    	$this->assign($data);
    	$this->assign('lawyer',$data1);
    	$this->assign('tags',$tag);
    	$this->assign('group_data',$group_data);
    	$this->assign('law_data',$res1);
    	$this->header();
    	$this->footer();
    	$this->display();
    	
    }
    
    //确认信息
    public function confirm(){
    	header("Content-Type: text/html; charset=UTF-8");
    	$lawyer = M("lawyer");
    	$order = M("order");
    	if(I('post.id')){
    		if($_SESSION['user_id']==""){
    			alertBack("请登录");
    		}
    		//判断是否生成订单
    		$data2['uid'] = $_SESSION['user_id'];
    		$data2['lawid'] = I('post.id');
    		$res3 = $order->where($data2)->find();
    		if($res3){
    			alertBack("已生成该律师订单，请查看状态");
    		}
    		
    		//判断该律师是否存在
    		$data1['t_lawyer.id'] = I('post.id');
    		$res1 = $lawyer
    		->where($data1)
    		->field("t_lawyer.*,t_law.title as law_title")
    		->join("left join t_law ON t_lawyer.lawid = t_law.id")
    		->find();
    		if ($res1){
    			//生成订单
    			$where['order']=time().rand(100000,999999);//订单编号
    			$where['uid'] = $_SESSION['user_id'];
    			$where['lawid'] = $res1['id'];
    			$where['price'] = $res1['price'];
    			$where['title'] = $res1['title'];
    			$where['description'] = $res1['law_title'];
    			$where['addtime'] = time();
    			$where['type'] = 1;
    			$res2 = $order->add($where);
    			if($res2){
    				alertReplace("订单提交成功");
    			}else{
    				alertReplace("订单提交失败");
    			}
    		}else{
    			alertBack("数据出错");
    		}
    	}

    	 $data['t_lawyer.id'] =  I('get.id');
    	$res = $lawyer
    	->where($data)
    	->join("left join t_law ON t_lawyer.lawid = t_law.id")
    	->field("t_lawyer.*,t_law.title as law_title")
    	->find();
    	$this->assign($res);
    	$this->header();
    	$this->footer();
    	$this->display();
    }
    //提交订单
    public function placed(){
    	$lawyer = M("lawyer");
    	$id = I('get.id');
    	if(I('get.id')){
    		$res = $lawyer->find($id);
    		if($res){
    			
    		}else{
    			alertLocation("数据出错","/");
    		}
    	}else{
    		alertLocation("数据出错","/");
    	}
    }
    
    
    
    
}