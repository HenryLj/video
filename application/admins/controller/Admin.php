<?php
namespace app\admins\controller;
use think\Controller;
use Util\data\Sysdb;
use think\Db;
header("Content-type: text/html; charset=utf-8"); 
/**
 * 管理员管理
 */
class Admin extends BaseAdmin
{
	//管理员列表
	public function index(){
		$data['lists'] = Db::table('admins')->order('id asc')->select();
		$data['groups'] = $this->db->table('admin_groups')->cates('gid');
		$this->assign('data',$data);
		return $this->view->fetch('index');
	}
	//添加管理员
	public function add(){
		$id = (int)input('get.id');
		//加载管理员
		$data['item'] = $this->db->table('admins')->where(array('id'=>$id))->item();
		//加载角色
		$data['groups'] = $this->db->table('admin_groups')->cates('gid');
		$this->assign('data',$data);
		return $this->view->fetch('add');
	}
	//保存管理员
	public function save(){
		$id = (int)$_POST['id'];
		$data['username'] = trim(input('post.username'));
		$data['gid'] = (int)input('post.gid');
		$password = trim(input('post.pwd'));
		$data['truename'] = trim(input('post.truename'));
		$data['status'] = (int)input('post.status');
		
		if(!$data['username']){
			exit(json_encode(array('code'=>1,'msg'=>'用户名不能为空'),JSON_UNESCAPED_UNICODE));
		}
		if(!$data['gid']){
			exit(json_encode(array('code'=>1,'msg'=>'角色不能为空'),JSON_UNESCAPED_UNICODE));
		}
		if($id==0&&!$password){
			exit(json_encode(array('code'=>1,'msg'=>'密码不能为空'),JSON_UNESCAPED_UNICODE));
		}
		if(!$data['truename']){
			exit(json_encode(array('code'=>1,'msg'=>'姓名不能为空'),JSON_UNESCAPED_UNICODE));
		}
		if($password){//用户编辑时有修改密码则加密
			$data['password'] = md5($password);
		}
		$res = true;
		if($id==0){
			//检查username是否已存在
			$item = $this->db->table('admins')->where(array('username'=>$data['username']))->item();
			if($item){
				exit(json_encode(array('code'=>1,'msg'=>'用户名已存在！'),JSON_UNESCAPED_UNICODE));
			}
			$data['add_time'] = time();
			//保存用户
			$res = $this->db->table('admins')->insert($data);
		}else{
			$this->db->table('admins')->where(array('id'=>$id))->update($data);
		}
		
		
		if(!$res){
			exit(json_encode(array('code'=>1,'msg'=>'保存失败'),JSON_UNESCAPED_UNICODE));
		}
		exit(json_encode(array('code'=>0,'msg'=>'保存成功'),JSON_UNESCAPED_UNICODE));
	}
	//删除管理员
	public function delete(){
		$id = $_POST['id'];
		$res = $this->db->table('admins')->where(array('id'=>$id))->delete();
		if(!$res){
			exit(json_encode(array('code'=>1,'msg'=>'删除失败'),JSON_UNESCAPED_UNICODE));
		}
		exit(json_encode(array('code'=>0,'msg'=>'删除成功')));
	}
}