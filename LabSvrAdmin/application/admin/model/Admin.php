<?php
namespace app\admin\model;

use think\Model;
use think\facade\Session;

class Admin extends Model{
	
    protected $table = 'lab_admin'; //指定数据表

    public static function login($data){
        $result = Admin::where('user_no', $data['username'])->find();

        if (sha1($result['passwd']) == sha1($data['password'])) {
            // 赋值think作用域
            session::set('admin_user', $result, 'admin');
            //更新登录信息
            $admin = Admin::get($result['id']);
            $admin->last_login_ip = get_ip();
            $admin->last_login_time = time();
            $admin->save();
            
            return json(['code' => 200, 'msg' => '成功登录']);
        } else {
            return json(['code' => 0, 'msg' => '账号或密码不正确']);
        }
    }
}