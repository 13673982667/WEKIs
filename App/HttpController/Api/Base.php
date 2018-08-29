<?php

/**
 * @Author: cuixudong123
 * @Date:   2018-08-07 03:05:19
 * @Last Modified by:   Administrator
 * @Last Modified time: 2018-08-29 14:16:13
 */
namespace App\HttpController\Api;

use EasySwoole\Core\Http\AbstractInterface\Controller;

// use \App\Common\Redis;
class Base extends Controller {
	public $public_path = 'http://192.168.0.102';
	function index() {
		$this->response()->write('$test->test()222');
		// $this->writeJson(200,"['asd'=>'asd']",'asdasdasd');
	}

	protected function writeJson($Code = 200, $msg = null, $result = null, $statusCode = 200) {

		if ($msg == '') {
			if ($Code == 1) {
				$msg = 'ok';
			} else if ($Code == 0) {
				$msg = 'error!';
			} else if ($Code == 2) {
				$msg = '参数错误!';
			}
		}

		if (!$this->response()->isEndResponse()) {
			$data = Array(
				"code" => $Code,
				"msg" => $msg,
				"data" => $result,
			);
			$this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			$this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
			$this->response()->withStatus($statusCode);
			return true;
		} else {
			trigger_error("response has end");
			return false;
		}
	}

}