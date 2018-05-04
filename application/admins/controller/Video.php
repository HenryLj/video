<?php
namespace app\admins\controller;
use app\admins\controller\BaseAdmin;

class Video extends BaseAdmin{
	//影片列表
	public function index(){
		$data['pageSize'] = 15;
		$data['page'] = max(1,(int)input('get.page'));
		//搜索词
		$data['wd'] = trim(input('get.wd'));
		$where = array();
		$data['wd'] && $where = 'title like "%'.$data['wd'].'%"';
		$data['data'] = $this->db->table('video')->where($where)->order('id desc')->pages($data['pageSize']);
		$label_ids = [];
		foreach ($data['data']['lists'] as $key => $value) {
			!in_array($value['channel_id'], $label_ids) && $label_ids[] = $value['channel_id'];
			!in_array($value['charge_id'], $label_ids) && $label_ids[] = $value['charge_id'];
			!in_array($value['area_id'], $label_ids) && $label_ids[] = $value['area_id'];
		}
		$label_ids && $data['labels'] = $this->db->table('video_label')->where('id in('.implode(',',$label_ids).')')->cates('id');
		$this->assign('data',$data);
		return $this->fetch();
	}
	//添加编辑
	public function add(){
		$data['channel'] = $this->db->table('video_label')->where(array('flag'=>'channel'))->lists();
		$data['charge'] = $this->db->table('video_label')->where(array('flag'=>'charge'))->lists();
		$data['areas'] = $this->db->table('video_label')->where(array('flag'=>'area'))->lists();

		$id = (int)input('get.id');
		$data['item'] = $this->db->table('video')->where(array('id'=>$id))->item();
		$this->assign('data',$data);
		return $this->fetch();
	}
	//保存
	public function save(){
		$id = (int)input('post.id');
		$data['title'] = trim(input('post.title'));
		$data['channel_id'] = (int)input('post.channel_id');
		$data['charge_id'] = (int)input('post.charge_id');
		$data['area_id'] = (int)input('post.area_id');
		$data['img'] = trim(input('post.img'));
		$data['url'] = trim(input('post.url'));
		$data['keywords'] = trim(input('post.keywords'));
		$data['desc'] = trim(input('post.desc'));
		$data['status'] = (int)input('post.status');

		if($data['title'] == ''){
			exit(json_encode(array('code'=>1,'msg'=>'影片名称不能为空')));
		}
		if($data['url'] == ''){
			exit(json_encode(array('code'=>1,'msg'=>'影片地址不能为空')));
		}

		if($id){
			$this->db->table('video')->where(array('id'=>$id))->update($data);
		}else{
			$data['add_time'] = time();
			$this->db->table('video')->insert($data);
		}
		exit(json_encode(array('code'=>0,'msg'=>'保存成功')));
	}
	// 图片上传
	public function upload_img(){
		$file = request()->file('file');
		if($file==null){
			exit(json_encode(array('code'=>1,'msg'=>'没有文件上传')));
		}
		$info = $file->move(ROOT_PATH.'public'.DS.'uploads');
		$ext = ($info->getExtension());
		if(!in_array($ext,array('jpg','jpeg','gif','png'))){
			exit(json_encode(array('code'=>1,'msg'=>'文件格式不支持')));
		}
		$img = '/uploads/'.$info->getSaveName();
		exit(json_encode(array('code'=>0,'msg'=>$img)));
	}

	// 删除
	public function delete(){
		$id = (int)input('post.id');
		$this->db->table('video')->where(array('id'=>$id))->delete();
		exit(json_encode(array('code'=>0,'msg'=>'删除成功')));
	}
}