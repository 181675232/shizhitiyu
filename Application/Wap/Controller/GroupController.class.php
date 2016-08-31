<?php
namespace Wap\Controller;
class GroupController extends CommonController {
	
    public function index(){

    	
    }

    //小组详情
    public function detail(){
        if(I('get.id')){
            $id = I('get.id');
            $group = M('group');
           // $where['t_group.typeid'] = $this->type_id;
            $where['t_group.id'] = $id;
            $group_data =   $group
                ->join("left join t_match on t_group.matchid=t_match.id")
                ->where($where1)->order("t_group.ord asc,t_group.id desc")
                ->field("t_group.*,t_match.title as match_title")
                ->where($where)->find();
            $this->assign($group_data);
        }
        $this->header();
        $this->footer();
        $this->display();

    }

    //比赛详情
    public function sign(){
        $group = M('group');
        if(I('post.id')){
            $group_user = M('group_user');
            $id = I('post.id');
            $res = $group->where("id=$id")->find();
            $where2['uid']      = $where1['uid'] = $_SESSION['user_id'];
            $where2['groupid']  = $where1['groupid']  = $id;//小组id
            $where2['matchid']  = $where1['matchid']  = $res['matchid'];//比赛id
            $where2['typeid']   = $where1['typeid']   = $res['typeid'];
            $where1['price']    = $res['price'];
            $where1['addtime']  = time();
            if($group_user->where($where2)->find()){//判断是否已经报名
                alertLocation('已报名！', '/group/sign/id/'.$id);
            }else{
                $res1 = $group_user->add($where1);
                if($res1){
                    alertLocation('报名成功！', '/group/sign/id/'.$id);
                }
            }
            exit();
        }
        $id = I('get.id');
        $where['t_group.id'] = $id;
        $group_data =   $group
                ->join("left join t_match on t_group.matchid=t_match.id")
                ->where($where1)->order("t_group.ord asc,t_group.id desc")
                ->field("t_group.*,t_match.title as match_title,t_match.sign_start_time,t_match.sign_end_time")
                ->where($where)->find();
        $this->assign($group_data);
        $this->header();
        $this->footer();
        $this->display();
    }


    
    
    
}