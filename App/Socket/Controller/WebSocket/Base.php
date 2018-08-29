<?php
namespace App\Socket\Controller\WebSocket;

use EasySwoole\Core\Socket\AbstractInterface\WebSocketController;
use EasySwoole\Core\Swoole\ServerManager;
use EasySwoole\Core\Swoole\Task\TaskManager;
use \App\Socket\Logic\Room as Rm;

class Base extends WebSocketController
{


    /**
     * 访问找不到的action
     * @param  ?string $actionName 找不到的name名
     * @return string
     */
    public function actionNotFound(?string $actionName)
    {
        $this->response()->write("action call {$actionName} not found");
    }

    protected function writeJson($Code = 200,$type = null,$result = null,$msg = null){
    	$data = $this->json($Code,$type,$result,$msg);
        $this->response()->write($data);
    }
    protected function json($Code = 200,$type = null,$result = null,$msg = null){
        if ($msg == '') {
            if ($Code == 1) {
                $msg = 'ok';
            } else if ($Code == 0) {
                $msg = 'error!';
            } else if ($Code == 2) {
                $msg = '参数错误!';
            }
        }
        if(is_null($type)){
            $type = $this->request()->getAction();
        }
        $data = Array(
            "code"=>$Code,
            'type'=>$type,
            "data"=>$result,
            "msg"=>$msg
        );
        return json_encode($data);
    }
      /**
     * 发送信息到房间
     */
    public function sendToRoom($roomId, $message = '')
    {

        // 注：单例Redis 可以将获取$list操作放在TaskManager中执行
        // 连接池的Redis 则不可以, 因为默认Task进程没有RedisPool对象。
        $list = Rm::selectRoomFd($roomId);
        //异步推送
        TaskManager::async(function ()use($list, $roomId, $message){
            foreach ($list as $fd) {
                // echo $fd.'--'.ServerManager::getInstance()->getServer()->exist($fd)."\n";
                if(ServerManager::getInstance()->getServer()->exist($fd)){
                    ServerManager::getInstance()->getServer()->push((int)$fd, $message);
                }
            }
        });
    }
     /**
     * 发送信息到房间
     */
    public function sendToUserList($list, $message = '')
    {
        // 注：单例Redis 可以将获取$list操作放在TaskManager中执行
        // 连接池的Redis 则不可以, 因为默认Task进程没有RedisPool对象。
        //异步推送
        TaskManager::async(function ()use($list, $message){
            foreach ($list as $fd) {
                // echo $fd.'--'.ServerManager::getInstance()->getServer()->exist($fd)."\n";
                if(ServerManager::getInstance()->getServer()->exist($fd)){
                    ServerManager::getInstance()->getServer()->push((int)$fd, $message);
                }
            }
        });
    }
}