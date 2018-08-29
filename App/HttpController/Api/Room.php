<?php
namespace App\HttpController\Api;


use EasySwoole\Core\Http\AbstractInterface\Controller;
use EasySwoole\Core\Swoole\ServerManager;
use \think\Db;
use \App\Common\Model\Room as MRoom;
use \App\Common\Model\Users;
use \App\Socket\Logic\Room as Rm;

// use App\Common\Model\Room;
// use App\HttpController\Api;

// use \App\Common\Redis;
class Room extends Base
{
	

   /* public function getRoomInfo(){
    	if(!$roomId = I('roomId')){
    		return $this->writeJson(2);
    	}
    	$room = new MRoom;
    	$where = [
    		'id' => $roomId
    	];
    	$res = $room->setval('map',$where)->getInfo(); 
    	if($res){
            $Users = new Users;
            if($uId = I('uId')){
                if($r = Users::get($uId)){
                    $res['UserInfo'] = $r;
                }
            }
            $userArr = Rm::selectRoomUserId($roomId);
            $res['userArr'] = $Users->where('id','IN',$userArr)->select();
                
    		return $this->writeJson(1,$res);
    	}
   		return $this->writeJson();
    }*/
}