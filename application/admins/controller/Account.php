<?php
namespace app\admins\controller;
use think\Controller;
use Util\data\Sysdb;

/**
 * 后台管理系统登陆入口
 */
class Account extends Controller{
	public function login(){
		return $this->fetch();
	}

	//管理员登陆
	public function dologin(){
		$username = trim(input('post.username'));
		$pwd = trim(input('post.pwd'));
		$verifycode = trim(input('post.verifycode'));
		
		if($username == ''){
			exit(json_encode(array('code'=>1,'msg'=>'用户名不能为空')));
		}
		if($pwd == ''){
			exit(json_encode(array('code'=>1,'msg'=>'密码不能为空')));
		}
		if($verifycode == ''){
			exit(json_encode(array('code'=>1,'msg'=>'验证码不能为空')));
		}
		//检验验证码
		if(!captcha_check($verifycode)){
			exit(json_encode(array('code'=>1,'msg'=>'验证码错误')));
		}
		//验证用户
		$this->db = new Sysdb;
		$admin = $this->db->table('admins')->where(array('username'=>$username))->item();
		if(!$admin){
			exit(json_encode(array('code'=>1,'msg'=>'用户不存在')));
		}
		if(md5($pwd) != $admin['password']){
			exit(json_encode(array('code'=>1,'msg'=>'密码错误')));
		}
		if($admin['status']==1){
			exit(json_encode(array('code'=>1,'msg'=>'用户已被禁用')));
		}
		//设置用户session
		session('admin',$admin);
		exit(json_encode(array('code'=>0,'msg'=>'登陆成功')));
	}

	//退出登陆
	public function logout(){
		session('admin',null);
		exit(json_encode(array('code'=>0,'msg'=>'退出登陆')));
	}
}