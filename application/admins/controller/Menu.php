<?php
/**
 * 菜单管理
 */
namespace app\admins\controller;
use app\admins\controller\BaseAdmin;

class Menu extends BaseAdmin{
	//菜单列表
	public function index(){
		$pid = (int)input('get.pid');
		$data['lists'] = $this->db->table('admin_menus')->where(array('pid'=>$pid))->lists();
		//返回上一级菜单
		$backid = 0;
		if($pid>0){
			$parent = $this->db->table('admin_menus')->where(array('mid'=>$pid))->item();
			$backid = $parent['pid'];
		}
		$this->assign('pid',$pid);
		$this->assign('backid',$backid);
		$this->assign('data',$data);
		return $this->view->fetch('index');
	}
	//保存数据
	public function save(){
		$pid = (int)$_POST['pid'];
		$ords = $_POST['ords'];
		$titles = $_POST['titles'];
		$controllers = $_POST['controllers'];
		$methods = $_POST['methods'];		
		$ishiddens = input('post.ishiddens/a');
		$status = input('post.status/a');

		foreach ($ords as $key => $value) {
			$data['pid'] = $pid;
			$data['ord'] = $value;
			$data['title'] = $titles[$key];
			$data['controller'] = $controllers[$key];
			$data['method'] = $methods[$key];
			$data['ishidden'] = isset($ishiddens[$key]) ? 1 : 0;
			$data['status'] = isset($status[$key]) ? 1 : 0;
			if($key==0&&$data['title']!=''){//新增的数据
				$this->db->table('admin_menus')->insert($data);
			}else{
				if($data['title']==''&&$data['controller']==''&&$data['method']==''){
					//删除该数据
					$this->db->table('admin_menus')->where(array('mid'=>$key))->delete();
				}else{
					//修改数据
					$this->db->table('admin_menus')->where(array('mid'=>$key))->update($data);
				}
			}
		}
		exit(json_encode(array('code'=>0,'msg'=>'保存成功')));
	}
	// 保存菜单
	public function save1(){
		$pid = (int)input('post.pid');
		$ords = input('post.ords/a');
		$titles = input('post.titles/a');
		$controllers = input('post.controllers/a');
		$methods = input('post.methods/a');
		$ishiddens = input('post.ishiddens/a');
		$status = input('post.status/a');

		foreach ($ords as $key => $value) {
			// 新增
			$data['pid'] = $pid;
			$data['ord'] = $value;
			$data['title'] = $titles[$key];
			$data['controller'] = $controllers[$key];
			$data['method'] = $methods[$key];
			$data['ishidden'] = isset($ishiddens[$key]) ? 1 : 0;
			$data['status'] = isset($status[$key]) ? 1 : 0;

			if($key==0 && $data['title']){
				$this->db->table('admin_menus')->insert($data);
			}
			if($key > 0){
				if($data['title']=='' && $data['controller']=='' && $data['method'] == ''){
					// 删除
					$this->db->table('admin_menus')->where(array('mid'=>$key))->delete();
				}else{
					// 修改
					$this->db->table('admin_menus')->where(array('mid'=>$key))->update($data);
				}
			}			
		}
		exit(json_encode(array('code'=>0,'msg'=>'保存成功')));
	}
}