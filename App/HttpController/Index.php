<?php
namespace App\HttpController;


use EasySwoole\Core\Http\AbstractInterface\Controller;
use EasySwoole\Core\Swoole\ServerManager;
use App\Common\Lib\Redis\CaChe;

class Index extends Controller
{

    function index()
    {   

        // CaChe::set('ad','asdad',10);
        // p(CaChe::get('ad'));
        // $res = \App\Common\Lib\Ali\Common::sendSms(13673982667,'123456');

        // $this->response()->write(json_encode($res)); 
        // require_once dirname(__DIR__).'/Common/Lib/ali/Test.php';
        // echo dirname(__DIR__);
        // TODO: Implement index() method.
        // $content = file_get_contents(__DIR__.'/websocket.html');
        $this->response()->write('$content');

    }

    /*
     * 请调用who，获取fd
     * http://ip:9501/push/index.html?fd=xxxx
     */
    function push()
    {
        $fd = intval($this->request()->getRequestParam('fd'));
        $info = ServerManager::getInstance()->getServer()->connection_info($fd);
        if(is_array($info)){
            ServerManager::getInstance()->getServer()->push($fd,'push in http at '.time());
        }else{
            $this->response()->write("fd {$fd} not exist");
        }
    }

}