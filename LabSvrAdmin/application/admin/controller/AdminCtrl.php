<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use app\admin\model\Admin;

class AdminCtrl extends Controller{
	public function hello(){
		echo "测试路由可用！";
	}

	public function home(){
		return $this->fetch("/index");
	}

	public function admin(){
		return $this->fetch("/login");
	}

	public function login(){
		$data=input('post.');
		$result = Admin::login($data);
        return $result;
	}

	public function getAdminList(){
		$result = Db::table('lab_admin')->all();
		
		
        return json([
        	"code"=>0
        	,"msg"=>""
        	,"count"=>20
        	,"data"=>[
        	     ["id"=>10000,"name"=>"李韦鹏","ip"=>"女","latest"=>"城市-0","greate"=>"签名-0", "update"=>255]
        	    ,["id"=>10000,"name"=>"user-0","ip"=>"女","latest"=>"城市-0","greate"=>"签名-0", "update"=>255]
        	    ,]
        	 ]
        );
	}
}