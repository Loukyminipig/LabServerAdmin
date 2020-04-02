<?php
namespace app\origin\controller;

use think\Controller;
use think\Db;

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
}