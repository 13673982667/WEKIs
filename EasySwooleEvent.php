<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/1/9
 * Time: 下午1:04
 */

namespace EasySwoole;

// 引入Di
use \App\Socket\Parser\WebSocket;
use \EasySwoole\Core\AbstractInterface\EventInterface;
use \EasySwoole\Core\Http\Request;
//注意：在此文件引入以下命名空间
use \EasySwoole\Core\Http\Response;
// 注意这里是指额外引入我们上文实现的解析器
use \EasySwoole\Core\Swoole\EventHelper;
// 引入上文Redis连接
use \EasySwoole\Core\Swoole\EventRegister;
use \EasySwoole\Core\Swoole\ServerManager;
use \think\db;

Class EasySwooleEvent implements EventInterface {

	public static function frameInitialize(): void{
		// TODO: Implement frameInitialize() method.
		date_default_timezone_set('Asia/Shanghai');
		// 获得数据库配置
		$dbConf = Config::getInstance()->getConf('database');
		// 全局初始化
		Db::setConfig($dbConf);
		// Di::getInstance()->set(SysConst::HTTP_CONTROLLER_MAX_DEPTH, 5);
	}

	// public static function mainServerCreate(ServerManager $server,EventRegister $register): void
	// {
	//     // TODO: Implement mainServerCreate() method.
	// }

	public static function onRequest(Request $request, Response $response): void{
		$_REQUEST = $request->getRequestParam();
		$_GET = $request->getQueryParams();
		unset($_GET['url']);
		$_POST = $request->getParsedBody();
		// unset($_POST['url']);

		// TODO: Implement onRequest() method.\
	}

	public static function afterAction(Request $request, Response $response): void {
		// TODO: Implement afterAction() method.
	}
	public static function mainServerCreate(ServerManager $server, EventRegister $register): void {
		include_once './App/Common/Common.php';
		// echo './App/Common/Common.php';
		// 注册Redis 从Config中读取Redis配置
		// Di::getInstance()->set('REDIS', new Redis(Config::getInstance()->getConf('REDIS')));
		// TODO: Implement mainServerCreate() method.
		// EventHelper::registerDefaultOnMessage($register,\App\Parser::class);
		// 注意一个事件方法中可以注册多个服务，这里只是注册WebSocket解析器
		// 注册WebSocket解析器
		EventHelper::registerDefaultOnMessage($register, WebSocket::class);
		// EventHelper::registerOnOpen($register);
		// EventHelper::registerOnClose($register);

	}

}