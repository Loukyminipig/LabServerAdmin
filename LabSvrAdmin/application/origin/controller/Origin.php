<?php
namespace app\origin\controller;

use think\Controller;
use think\Db;
use think\facade\Request;

use app\origin\model\Item;

class Origin extends Controller{
	
	public function hello(){
		echo "hello origin <br/>";
	}

	public function addDemo(){
		Item::create([
			'name'=>'liweipeng',
			'status'=>2
		],['name', 'status']);
	}

	public function getAllDemo(){
		// $result = Item::all()->order('name');
		// dump($result);
		$result = Db::table('think_data')->all();
		dump($result);
	}

	public function delDemo(){
		$result = Item::where('name', 'liweipeng')->delete();
		dump($result);
	}

	public function upDemo(){
		$result = Item::where('name', 'liweipeng')->find();
		$result->name = 'louky';
		$result->save();
	}

	public function getJsonDemo(){
		// $data = Item::where('name', 'Bob')->find(); //查找一条记录
		$data = Item::where('name', 'Bob')->select(); //查找多条记录
		echo json_encode($data);
	}

	public function show_ui(){
		return $this->fetch();
	}

	public function test(){
		$index = 0;
		return getDevTypeStr($index);
	}

	/*
* 获取用户真实IP地址
*/
public function get_ip()
{
	if(!empty($_SERVER['HTTP_CLIENT_IP'])){
	$cip = $_SERVER['HTTP_CLIENT_IP'];
	}
	else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	else if(!empty($_SERVER["REMOTE_ADDR"])){
	$cip = $_SERVER["REMOTE_ADDR"];
	}else{
	$cip = '';
	}
	preg_match("/[\d\.]{7,15}/", $cip, $cips);
	$cip = isset($cips[0]) ? $cips[0] : 'unknown';
	unset($cips);
	return $cip;
}

function get_real_ip1(){ 
	$ip=false; 
	if(!empty($_SERVER['HTTP_CLIENT_IP'])){ 
		$ip=$_SERVER['HTTP_CLIENT_IP']; 
	}
	if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ 
		$ips=explode (', ', $_SERVER['HTTP_X_FORWARDED_FOR']); 
		if($ip){ array_unshift($ips, $ip); $ip=FALSE; }
		for ($i=0; $i < count($ips); $i++){
			if(!preg_match ('/^(10│172.16│192.168)./', $ips[$i])){
				$ip=$ips[$i];
				break;
			}
		}
	}
	return ($ip ? $ip : $_SERVER['REMOTE_ADDR']); 
}

function get_real_ip2(){
    static $realip;
    if(isset($_SERVER)){
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $realip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }else if(isset($_SERVER['HTTP_CLIENT_IP'])){
            $realip=$_SERVER['HTTP_CLIENT_IP'];
        }else{
            $realip=$_SERVER['REMOTE_ADDR'];
        }
    }else{
        if(getenv('HTTP_X_FORWARDED_FOR')){
            $realip=getenv('HTTP_X_FORWARDED_FOR');
        }else if(getenv('HTTP_CLIENT_IP')){
            $realip=getenv('HTTP_CLIENT_IP');
        }else{
            $realip=getenv('REMOTE_ADDR');
        }
    }
    return $realip;
}

// 获取IP地址（摘自discuz）
function getIp(){
	$ip='未知IP';
	if(!empty($_SERVER['HTTP_CLIENT_IP'])){
		return is_ip($_SERVER['HTTP_CLIENT_IP'])?$_SERVER['HTTP_CLIENT_IP']:$ip;
	}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		return is_ip($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$ip;
	}else{
		return is_ip($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:$ip;
	}
}
function is_ip($str){
	$ip=explode('.',$str);
	for($i=0;$i<count($ip);$i++){  
		if($ip[$i]>255){  
			return false;  
		}  
	}  
	return preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/',$str);  
}
}