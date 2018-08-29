<?php
/**
 * Created by PhpStorm.
 * User: baidu
 * Date: 17/7/27
 * Time: 下午5:57
 */
namespace App\Common\Model;
use think\Model;

class Base extends Model {

	// 开启自动写入时间戳字段
	protected $autoWriteTimestamp = true;
	public $page = 1;
	public $size = 10;
	public $from = 0;
	public $map = []; //where条件
	public $order = ''; //排序
	protected $res = [];

	public function initialize() {
		// echo 'Model init';
		// parent::__construct();
		parent::initialize();
		// //初始化分业数据
		$this->getPageAndSize();
	}

	/**
	 * 获取分页page size 内容
	 */
	public function getPageAndSize() {
		$this->page = intval(I('page')) ? intval(I('page')) : $this->page;
		$this->size = intval(I('size')) ? intval(I('size')) : $this->size;
		$this->from = ($this->page - 1) * $this->size;
	}

	//设置 $key => $value
	public function setval($v, $k) {
		$this->$v = $k;
		return $this;
	}
}