<?php
namespace Home\Controller;
class AboutController extends CommonController {
	
    public function index(){
	     $about= M("about");
	     $res = $about->order('ord asc,id asc')->select();
	     $this->assign('about',$res);
	     $this->header();
	     $this->footer();
	     $this->display();
    }
    
}