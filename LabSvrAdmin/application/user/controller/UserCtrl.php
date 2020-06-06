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
		return json(404, '禁止访问', 0, null);
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
			$one_day = 24*60*60;
			for($i=0; $i<$count; $i++){
				$info = $result[$i]['user_name'].' '.$result[$i]['user_no'].' '.$result[$i]['user_dept'];
				$stime = date('Y-m-d',$result[$i]['out_time']);  //24*60*60 = 86400
				$etime = date('Y-m-d',$result[$i]['out_time'] + $result[$i]['days'] * 86400);
				$data[$i]=["id"=>$no
				          ,"info"=>$info
				          ,"tel"=>$result[$i]['user_tel']
				          ,"stime"=>$stime
				          ,"etime"=>$etime];
			}
			return json(200, '已借用设备列表', $passCount, $data);
		}
		return json(404, '禁止访问', 0, null);
	}

	public function applyDev(){ //测试的时候需要注意测试数据的学号和姓名不应该是重复的！！！
		if($this->request->isPost()){
			$code = 400;
			$info = "申请失败，已经当前用户存在相同申请";
			$uno =  $this->request->param('uno');
			$dno = $this->request->param('dno');
			$durdate = $this->request->param('durdate');
			$days = $this->request->param('days');
			$reason = $this->request->param('reason');
			$notes = $this->request->param('notes');
			$data = array();
			if(UserCtrl::checkDevApply($uno, $dno)){
				$dev_result = Db::table('lab_dev')->where('dev_no', $dno)->find();
				$user_result = Db::table('lab_user')->where('user_no', $uno)->find();
				$code = 401; //用户或者设备不存在
				$info = "申请失败，用户或者设备不存在";	
				if($dev_result && $user_result){
					$data = ['dev_no'=>$dno, 'dev_type'=>$dev_result['dev_type'],
				    	    'dev_desc'=>$dev_result['dev_desc'], 'status'=>0,
				        	'apply_time'=>time(), 'user_name'=>$user_result['user_name'],
				         	'user_no'=>$user_result['user_no'], 'user_dept'=>$user_result['user_dept'],
				         	'user_tel'=>$user_result['user_tel'], 'days'=>$days, 'reason'=>$reason, 
				         	'notes'=> ($notes.'@'.$durdate)];
					$insert_res = Db::table('lab_dev_borrowed')->insert($data);
					$code = 402; //插入失败
					$info = "申请上传失败";	
					if($insert_res == 1){
						$code = 200;
						$info = "申请设备成功";
					}
				}
			}
			return json($code, $info, 0, null);
		}
		return json(404, '禁止访问', 0, null);
	}

	private function checkDevApply($uno, $dno){
		$result = Db::table('lab_dev_borrowed')->where(['dev_no'=>$dno,'user_no'=>$uno])->select();
		return $result == null;
	}
}