<?php
namespace app\origin\model;

use think\Model;

class Item extends Model{
	protected $table = 'think_data';
	
	public static function get_ip() {
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
}