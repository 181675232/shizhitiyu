<?php
namespace Admin\Controller;

class OrderController extends CommonController {
	
	public function index(){
		
		$table = M('order'); // 实例化User对象
		
		//接收查询数据
		if (I('get.keyword')){
			$keyword = I('get.keyword');
			$data['t_order.title'] = array('like',"%{$keyword}%");
			$data['t_user.phone'] = array('like',"%{$keyword}%");
			$data['_logic'] = 'or';
			$table = $table->join("left join t_user ON t_order.uid = t_user.id")->field("t_order.*,t_user.phone")->where($data);
		}elseif (I('get.state')){
			$data['t_order.state'] = I('get.state');
			$table = $table->where($data);
			$this->assign('verify',I('get.state'));
		}
		$count      = $table->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$res = $table
		->where($data)
		->join("left join t_user ON t_order.uid = t_user.id")
		->field("t_order.*,t_user.phone")
		->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('data',$res);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板			
	}
	
// 	public function add(){
// 		if (IS_POST){
// 			$table = D('order');
// 			if ($table->create()){
// 				if ($table->add()){
// 					alertLocation('添加会员成功！', '/Admin/Activity');
// 				}else {
// 					$this->error('添加失败！');
// 				}
// 			}else {
// 				$this->error($table->getError());
// 			}
			
// 		}
// 		$this->display();
// 	}
	
	public function edit(){
		$id = I('get.id');
		if (IS_POST){
			$table = M('order');
			if ($table->create()){
				$data = $table->create();
				$data['create_ts'] = strtotime($data['create_ts']);
				$data['avail_day'] = implode(',', $data['avail_day']);
				if ($table->save($data)){
					alertBack('修改成功！');
				}else {
					$this->error('没有任何修改！');
				}
			}else {
				$this->error($table->getError());
			}
				
		}
		$table = M('order');
		$data= $table
		->join("left join t_user ON t_order.uid = t_user.id")
		->field("t_order.*,t_user.phone,t_user.name")
		->where("t_order.id = $id")->find();

		$this->assign($data);
		$this->display();
	}
	
	public function state(){
		$data = I('get.');			
		$table = M('service_request');
		if ($table->save($data)){
			$this->redirect("/Admin/Order");
		}else {
			$this->error('没有任何修改！');
		}
	}
	
	public function delete(){		
		$post = implode(',',$_POST['id']);	
		$table = M('service_request');
		$data = $table->delete($post);
		if ($data){
			echo '删除成功！';
		}else {
			echo '删除失败！';
		}
	}
	
	public function EditOrderRemark(){
		$table = M('service_request');
		$where['ID'] = I('post.order_no');
		$where['NOTE'] = I('post.remark');
		if ($table->save($where)){
			$data['status'] = 1;
			$data['msg'] = '提示：修改成功！';		
			echo json_encode($data);
		}else {
			$data['msg'] = '提示：没有任何修改！';
			echo json_encode($data);
		}
	}
	
	public function EditRealAmount(){
		$table = M('service_request_bid');		
		$request_id = I('post.order_no');
		$net_total = $table->where("REQUEST_ID = $request_id and (BID_STATUS = 2 or BID_STATUS =4)")->find();
		$where['DISCOUNT'] = I('post.real_amount');
		$where['NET_TOTAL'] = $net_total['total_before_discount'] - I('post.real_amount');
		if ($table->where("REQUEST_ID = $request_id and (BID_STATUS = 2 or BID_STATUS =4)")->save($where)){
			$data['status'] = 1;
			$data['msg'] = '提示：修改成功！';
			echo json_encode($data);
		}else {
			$data['msg'] = '提示：没有任何修改！';
			echo json_encode($data);
		}
	}
	
	public function OrderCancel(){
		$table = M('service_request');
		$where['ID'] = I('post.order_no');
		$where['REQUEST_STATUS_ID'] = I('post.check_revert');
		if ($table->save($where)){
			$data['status'] = 1;
			$data['msg'] = '提示：取消成功！';
			echo json_encode($data);
		}else {
			$data['msg'] = '提示：已经是取消状态！';
			echo json_encode($data);
		}
	}
	
	
} 