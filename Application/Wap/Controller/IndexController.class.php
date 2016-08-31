<?php
namespace Wap\Controller;
class IndexController extends CommonController {

    public function index(){

        $banner = M('banner');
        $this->type_data;
        $where['typeid'] = $this->type_id;
        //幻灯
        $banner_data = $banner->where($where)->order("ord asc,id desc")->select();
        $this->assign("banner_data",$banner_data);

        //比赛小组
        $group = M('group');
        $where1['t_group.typeid'];
        $group_data = $group
            ->join("left join t_match on t_group.matchid=t_match.id")
            ->where($where1)->order("t_group.ord asc,t_group.id desc")
            ->field("t_group.*,t_match.title as match_title")
            ->select();
        $this->assign("group_data",$group_data);

        //新闻公告
        $news = M('news');
        $news_data = $news->where($where)->order("ord asc,id desc")->limit("0,4")->select();
        $this->assign("news_data",$news_data);
        //赞助单位
        $link = M('link');
        $link_data = $link->where($where)->order("ord asc,id desc")->select();
        $this->assign("link_data",$link_data);

        $this->header();
        $this->footer();
        $this->display();
    }
    
    
}