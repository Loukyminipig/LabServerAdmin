<?php
namespace app\user\controller;

use think\Controller;
use think\Db;

use think\facade\Request;

class UserCtrl extends Controller{
	public function login(){
		if($this->request->isPost()){
			$user = $this->request->param('user');
			$result = Db::table('lab_user')->where('user_no', $user)->find();
			if(sha1($result['passwd']) == ($this->request->param('pwd'))){
				$data = array("name"=>$result['user_name']
					         ,"dept"=>$result['user_dept']
					         ,"tel"=>$result['user_tel']);

				Db::table('lab_user')->where('user_no', $user)
				    ->data(['last_login_ip'=>get_ip(), 'last_login_time'=>time()])->update();
				return json(200, '登录成功', 1, $data);
			} else {
				return json(201, '登录失败', 0, null);
			}
		} 
		return json(404, '禁止访问', 0, null);
	}

	public function getDevList(){
		if($this->request->isGet()){
			$result = Db::table('lab_dev')->where('dev_type', $this->request->param('type'))->select();
			$count = count($result);
			$data = array();
			for($i=0; $i<$count; $i++){
				$wait = Db::table('lab_dev_borrowed')
							->where(['dev_no'=> $result[$i]['dev_no'],
								     'status'=>0])->select();
				$data[$i]=["id"=>$result[$i]['dev_no']
				          ,"name"=>$result[$i]['dev_desc']
				          ,"wait"=>count($wait)
				          ,"available"=>$result[$i]['available_count']];
			}
			return json(200, '设备列表', $count, $data);
		}
	}

	public function getBorrowedDevList(){
		if($this->request->isGet()){
			$no =  $this->request->param('no');
			$result = Db::table('lab_dev_borrowed')      //status ：当前申请状态
							->where(['dev_no'=>$no,      //0->待审 1->通过 2->正借出 3->已还 4->拒绝
								     'status'=>2])->select(); //仅列出“正借出”设备，通过审核待取设备
			$count = count($result);                          //在通知中知会用户
			$passCount = count(Db::table('lab_dev_borrowed')->where(['dev_no'=>$no,'status'=>1])->select()); //待取设备数量
			$data = array();
			for($i=0; $i<$count; $i++){
				$info = $result[$i]['user_name'].' '.$result[$i]['user_no'].' '.$result[$i]['user_dept'];
				$stime = date('Y-m-d',$result[$i]['out_time']);
				$etime = date('Y-m-d',$result[$i]['out_time'] + $result[$i]['days']*24*60*60);
				$data[$i]=["id"=>$no
				          ,"info"=>$info
				          ,"tel"=>$result[$i]['user_tel']
				          ,"stime"=>$stime
				          ,"etime"=>$etime];
			}
			return json(200, '已借用设备列表', $passCount, $data);
		}
	}
}