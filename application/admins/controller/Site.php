<?php
namespace app\admins\controller;
use app\admins\controller\BaseAdmin;

class Site extends BaseAdmin{

	public function index(){
		$site = $this->db->table('site')->where(array('names'=>'site'))->item();
		$site && $site['values'] = json_decode($site['values']);
		$this->assign('site',$site);
		return $this->fetch();
	}

	public function save(){
		$title = trim($_POST['title']);
		$site = $this->db->table('site')->where(array('names'=>'site'))->item();
		if(!$site){
			$this->db->table('site')->insert(array('names'=>'site','title'=>$title));
		}else{
			$value['values'] = json_encode($title);
			$this->db->table('site')->where(array('names'=>'site'))->update($value);
		}
		exit(json_encode(array('code'=>0,'msg'=>'保存成功')));
	}
}