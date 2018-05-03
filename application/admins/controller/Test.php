<?php
namespace app\admins\controller;
use think\Controller;
use Util\data\Sysdb;

/**
 * 
 */
class Test extends Controller
{
	public function index(){
		$this->db = new Sysdb;
		$res = $this->db->table('admins')->field('id,username')->lists();
		var_dump($res);
		echo("<hr>");
		$res2 = $this->db->table('admins')->field('id,username')->cates('id');
		var_dump($res2);
	}
}