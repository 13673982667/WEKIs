<?php
namespace App\Socket\Controller\WebSocket;

use EasySwoole\Core\Socket\AbstractInterface\WebSocketController;
use \App\Socket\Logic\Room as Rm;
use \App\Common\Model\Users;

class Room extends Base
{


    public function index()
    {
        $fd = $this->client()->getFd();
        $this->response()->write("you fd is {$fd}");
    }

    public function login(){
    	$data = $this->request()->getArg('data');
        if(!isset($data['uid'])){
            $this->writeJson(2);
        }
    	if($uid = $data['uid']){
    		$fd = $this->client()->getFd();
    		Rm::bindUser($uid, $fd);//insert Redis
    		$this->writeJson(1,'login');
    	}else{
    		$this->writeJson(0);
    	}
    	// Rm::
    }
    //jiaru fangjian 
    public function joinRoom(){
    	$data = $this->request()->getArg('data');
    	if(isset($data['roomId'])){
    		$uid = $data['uid'];
    		$roomId = $data['roomId'];
    		$fd = $this->client()->getFd(); 
    		Rm::joinRoom($roomId, $fd, $uid);//insert Redis 
            //hSet("room:{$roomId}", $fd, $userId)  
            //hSet('roomIdFdMap', $fd, $roomId);

            // guangbo .
			$Users = new Users;
            $this->sendToRoom($roomId,$this->json(1,'ToRoom',[
                'type'=>'ToRoom',
                'msg' => '进入房间',
                'uId' => $uid,
                'toId' => $roomId,
                'data' => $Users->where('user_id',$uid)->find(),
            ]));

    		$this->writeJson(1);
    	}else{
    		$this->writeJson(0);
    	}
    }
    //qunfa xiaoxi
    public function sendMsg(){
        $data = $this->request()->getArg('data');
        $roomId = $data['toId'];
        $type = $data['toType'];

        $this->sendToRoom($roomId,$this->json(1,'msg',[
            'type' => $type,
            'msg'  => '',
            'toId' => $roomId,
            'data' => $data,
        ]));
    }
    

}
