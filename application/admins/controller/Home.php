<?php
namespace app\admins\controller;
use think\Controller;
use Util\data\Sysdb;

/**
 *后台首页 
 */
class Home extends BaseAdmin{
	public function index(){
		return $this->fetch();
	}

	//欢迎页面
	public function welcome(){
		return $this->fetch();
	}

	public function test(){
		$content = file_get_contents("D:/yunshi/yunshi.php");
    	$content = json_decode($content,true);
        var_dump($content);
	}
}