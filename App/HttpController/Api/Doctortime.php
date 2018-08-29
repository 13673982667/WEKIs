<?php
namespace App\HttpController\Api;

use App\Common\Model\DoctorTime as DTime;
use App\Common\Model\Users;
use EasySwoole\Core\Swoole\Time\Timer;

// use \App\Common\Redis;
class Doctortime extends Base {

	//定时
	function tixing() {
		/**
		 * 延时调用
		 * @param int      $microSeconds 需要延迟执行的时间
		 * @param \Closure $func 定时器需要执行的操作 传入一个闭包
		 * @param mixed    $args 操作参数 此处传入的参数会按顺序传给闭包
		 * @return int 返回整数型的定时器编号 可以用该编号停止定时器
		 */
		if (!($phone = I('phone')) || !($microSeconds = intval(I('microSeconds')))) {
			return $this->writeJson(400, 'error');
		}
		Timer::delay($microSeconds * 1000, function () use ($phone) {
			$Users = new Users;
			$DTime = new DTime;
			$uId = $Users->where('phone', $phone)->value('id');
			$res = $DTime->where('uid', $uId)->find();
			if ($res) {
				if ($res['status'] == 0) {
					//预约状态是0的  人还没有来
					$this->send($phone, 1);
				}
			}
		});
		return $this->writeJson(200, 'ok');
	}

	//发送短信提醒
	public function send($phone, $str) {

		//导入第三方类库 Ucpaas.class.php
		include_once "./App/Common/Lib/Ucpaas.php";
		//初始化必填
		$options['accountsid'] = 'da776c1deccc22c682efce0cdf088ca2';
		$options['token'] = 'ad86c5251867b87a40f9602176c2b854';
		//实例化Ucpaas
		$Ucpaas = new \Ucpaas($options);
		//接入产品
		//短信验证码（模板短信）,默认以65个汉字（同65个英文）为一条（可容纳字数受您应用名称占用字符影响），超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。
		$appId = "e8501ac72bd0469cb5f0f3cc06030e06";
		//对象终端
		$to = $phone; //应该是获取到的手机号码
		//短信模板id
		$templateId = "368489";
		//参数 (验证码)
		$param = $str;
		//原样格式
		$data = $Ucpaas->templateSMS($appId, $to, $templateId, $param);
		// echo $data;
		$data = json_decode($data, true);

		if ($data['resp']['respCode'] == '000000') {
			//成功 存缓存
			// Cache::set('send_' . $phone, $param, 60);
			// return show(1, '', $param);
		}
		// return show(0);
	}

}