<?php
namespace Home\Controller;

class MoreController extends CommonController {
	
	//初始化数据
	public function index(){
		$list = M('news')->order('id DESC')->limit(10)->select();
		$this->assign('list', $list);
		$this->display();
	}
	
	//获取下一栏数据
	public function getMore(){
		$Action = new \Org\Util\More();
		$last_id = $Action->_get('last_id');
        $map['id'] = array('lt', $last_id);
        $list = M('news')->where($map)->order('id DESC')->limit(30)->select();
        $Action->ajaxReturn($list);
	}
    
    
    
}