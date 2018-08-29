<?php
namespace App\HttpController\api;

use EasySwoole\Core\Http\AbstractInterface\Controller;
use EasySwoole\Core\Swoole\ServerManager;
use EasySwoole\Core\Swoole\Time\Timer;
use \App\Common\Test\Test;

// use \App\Common\Redis;
class Index extends Controller {

	function index() {
		$test = new Test;
		$this->response()->write($test->test());
		// TODO: Implement index() method.
		// $content = file_get_contents(__DIR__.'../websocket.html');
		// $this->response()->write($content);
	}

	/*
		     * 请调用who，获取fd
		     * http://ip:9501/push/index.html?fd=xxxx
	*/
	function push() {
		$fd = intval($this->request()->getRequestParam('fd'));
		$info = ServerManager::getInstance()->getServer()->connection_info($fd);
		if (is_array($info)) {
			ServerManager::getInstance()->getServer()->push($fd, 'push in http at ' . time());
		} else {
			$this->response()->write("fd {$fd} not exist");
		}
	}

	function dingshi() {
		/**
		 * 延时调用
		 * @param int      $microSeconds 需要延迟执行的时间
		 * @param \Closure $func 定时器需要执行的操作 传入一个闭包
		 * @param mixed    $args 操作参数 此处传入的参数会按顺序传给闭包
		 * @return int 返回整数型的定时器编号 可以用该编号停止定时器
		 */
		Timer::delay(60 * 1000, function ($arr) {
			file_put_contents('text.php', json_encode($arr));
		}, 500);
	}

}