<?php
namespace app\admin\controller;

use think\Controller;

class AdminCtrl extends Controller{
	public function hello(){
		echo "测试路由可用！";
	}


	public function render(){
		return $this->fetch("Login/index");
	}
}