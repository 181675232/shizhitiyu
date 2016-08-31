<?php
namespace Wap\Controller;
class NewsController extends CommonController {
	
    public function index(){

    	
    }

    public function detail(){
        if(I('get.id')){
            $id = I('get.id');
            $news = M('news');
            $where['typeid'] = $this->type_id;
            $news_data =   $news->where($where)->find($id);
            $this->assign($news_data);
            $this->header();
            $this->footer();
            $this->display();
        }

    }

    
    
    
}