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
}