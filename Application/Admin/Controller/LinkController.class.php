<?php
namespace Admin\Controller;

class linkController extends CommonController {

	public function index(){
        $table = M('link'); // 实例化User对象
        $type = M('type');
        //接收查询数据

        if (I('get.keyword')){
            $keyword = I('get.keyword');
            $data['title'] = array('like',"%{$keyword}%");
        }

        if (I('get.type')){
            $data['type'] = I('get.type');
            $this->assign('type',I('get.type'));
        }
        $count      = $table->where($data)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $res = $table->where($data)	->order('ord asc,addtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $type_data = $type->order("id asc")->select();


        $this->assign('type_data',$type_data);
        $this->assign('data',$res);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); // 输出模板
	}


    public function add(){
        $table = M('link');
        if (I('post.')){
            $where = I('post.');
            $where['addtime'] = time();
            $res =$table->add($where);
            if ($res){
                alertLocation('添加成功！', '/Admin/link');
            }else {
                $this->error('添加失败！');
            }
        }
        $type = M('type');
        $type_data = $type->order("id asc")->select();
        $this->assign('type_data',$type_data);
        $this->display();

    }

        public function edit(){
            $table = M('link');
            $id = I('get.id');
            if (IS_POST) {
                $where = I('post.');
                $table->save($where);
                alertBack('修改成功！');
            }

            $data = $table->where("id = $id")->find();

            $type = M('type');
            $type_data = $type->order("id asc")->select();
            $this->assign('type_data', $type_data);
            $this->assign($data);
            $this->display();
        }

    public function state(){
        $data = I('get.');

        $str ='/';
        if (I('get.p')){
            $str.= 'p/'.I('get.p').'/';
        }

        if (I('get.keyword')){
            $str.= 'keyword/'.I('get.keyword').'/';
        }

        if (I('get.type')){
            $str.= 'type/'.I('get.type').'/';
        }
        $table = M('link');
        if ($table->save($data)){
            $this->redirect("/Admin/link/index".$str);
        }else {
            $this->error('没有任何修改！');
        }
    }

    public function delete(){
        $post = implode(',',$_POST['id']);
        $table = M('link');
        $data = $table->delete($post);
        if ($data){
            echo '删除成功！';
        }else {
            echo '删除失败！';
        }
    }


    public function ajaxstate(){
        $data = I('get.');
        $table = M('link');
        if ($table->save($data)){
            echo 1;
        }else {
            echo 0;
        }
    }
}