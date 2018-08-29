<?php

/**
 * @Author: cuixudong123
 * @Date:   2018-08-08 19:38:59
 * @Last Modified by:   Administrator
 * @Last Modified time: 2018-08-17 11:05:33
 */
namespace App\Socket\Controller\WebSocket;

use App\Common\Model\Room;
use App\Common\Model\Users;
use EasySwoole\Core\Swoole\Time\Timer;
use \App\Socket\Logic\Game as Gm;
use \App\Socket\Logic\Room as Rm;

class Game extends Base {
	public function gameStart() {
		$data = $this->request()->getArg('data');
		if (!isset($data['roomId'])) {
			return $this->writeJson(2);
		}
		$time = isset($data['time']) ? $data['time'] : 5;
		$roomId = $data['roomId'];
		//clear Redis Room
		// Gm::clearAll($roomId);
		$this->end($roomId);
		$startTime = getMicrotime();
		$endTime = $startTime + ($time * 1000);
		Gm::setEndTime($roomId, $endTime);
		$this->sendToRoom($roomId, $this->json(1, 'start', [
			'type' => 'start',
			'msg' => '',
			'data' => [
				'endTime' => $time,
			],
		]));
		Timer::delay(($time + 1) * 1000, function () use ($roomId) {
			$this->end($roomId);
		});
	}
	//jieshu
	public function end($roomId) {

		$res = Gm::getRoomUserListAll($roomId);
		$value = Gm::getRoomInc($roomId);
		$msg = '';
		if ($res) {
			$oneId = (array_keys($res))[0];
			$Users = new Users;
			$Users->where('user_id', $oneId)->setInc('pay_points', $value);
			$User = $Users->where('user_id', $oneId)->find();
			$msg = count($res) . '人参加,' . $User['nickname'] . '得到' . $value . '积分';
			//tongzhi zhongjiang yonghu
			$userFdlist = Rm::getUserFd($oneId);
			$this->sendToUserList($userFdlist, $this->json(1, 'setIncCb', [
				'type' => 'setIncCb',
				'msg' => 'OK',
				'value' => $value,
			]));
		}

		$this->sendToRoom($roomId, $this->json(1, 'ToMsg', [
			'type' => 'GameOver',
			'msg' => $msg,
			'uId' => '',
			'toId' => $roomId,
		]));
		Gm::clearAll($roomId);
	}
	public function add() {
		$data = $this->request()->getArg('data');
		if (!isset($data['uId']) || !isset($data['roomId'])) {
			return $this->writeJson(2);
		}
		$roomId = $data['roomId'];
		$uId = $data['uId'];
		$time = getMicrotime();
		if ($time > Gm::getEndTime($roomId)) {
			return $this->writeJson(3, 'GameAdd', [], '超时');
		}

		$Room = new Room();
		$where = [
			'id' => $roomId,
			'status' => 1,
		];
		if (!($value = $Room->where($where)->value('value'))) {
			return $this->writeJson(0);
		}

		if (Gm::getRoomUserList($roomId, $uId)) {
			return $this->writeJson(3, 'GameAdd', [], '参加过了');
		}
		Gm::addRoomUserList($roomId, $uId, $time);
		//增加积分池
		Gm::addRoomInc($roomId, $value);
		//减去个人积分
		$Users = new Users;
		if ($Users->where('user_id', $uId)->value('pay_points') < $value) {
			return $this->writeJson(3, 'GameAdd', [], '积分不够');
		}
		$Users->where('user_id', $uId)->setDec('pay_points', $value);
		// guangbo
		/*$this->sendToRoom($roomId,$this->json(1,'ToMsg',[
	            'type'=>'ToMsg',
				'msg' => '参加了',

	            'uId' => $uId,
	            'toId' => $roomId
*/
		return $this->writeJson(1, 'GameAdd', [], 'OK');
	}

}
