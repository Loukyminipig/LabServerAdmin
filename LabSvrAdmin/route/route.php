<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;
Route::rule('admin','admin/Admin_Ctrl/admin','GET');
Route::rule('home','admin/Admin_Ctrl/home','GET');
Route::rule('login','admin/Admin_Ctrl/login','POST');
Route::rule('adminlist','admin/Admin_Ctrl/getAdminList','GET');

Route::rule('userlogin','user/User_Ctrl/login','POST');
Route::rule('getDevList','user/User_Ctrl/getDevList','GET');
