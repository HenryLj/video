<?php
namespace app\admins\controller;
use app\admins\controller\BaseAdmin;

class Labels extends BaseAdmin{
	//频道管理
	public function channel(){
		$channel = $this->db->table('video_label')->where(array('flag'=>'channel'))->lists();
		$this->assign('data',$channel);
		return $this->fetch();
	}
	//资费管理
	public function charge(){
		$charge = $this->db->table('video_label')->where(array('flag'=>'charge'))->lists();
		$this->assign('data',$charge);
		return $this->fetch();
	}
	//地区管理
	public function area(){
		$area = $this->db->table('video_label')->where(array('flag'=>'area'))->lists();
		$this->assign('data',$area);
		return $this->fetch();
	}

	//保存
	public function save(){
		$flag = trim(input('post.flag'));
		$ords = input('post.ords/a');
		$titles = input('post.titles/a');
		$status = input('post.status/a');

		foreach ($ords as $key => $value) {
			//新增
			$data['flag'] = $flag;
			$data['ord'] = $value;
			$data['title'] = $titles[$key];
			$data['status'] = isset($status[$key]) ? 1 : 0;
			if($key > 0){
				if($data['title'] == ''){
					//删除数据
					$this->db->table('video_label')->where(array('id'=>$key))->delete();
				}else{
					//修改数据
					$this->db->table('video_label')->where(array('id'=>$key))->update($data);
				}
			}
		}
		exit(json_encode(array('code'=>0,'msg'=>'保存成功')));
	}
}